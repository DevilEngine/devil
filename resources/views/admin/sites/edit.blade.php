@extends('layout.admin')

@section('title', 'Update website')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Update website</h4>
        <hr>
        @if($errors->any())
            <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.sites.update', $site) }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" value="{{ old('name', $site->name) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">URL</label>
                <input type="url" name="url" class="form-control" value="{{ old('url', $site->url) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mirror URL 1 (facultatif)</label>
                <input type="url" name="mirror_1" class="form-control" value="{{ old('mirror_1', $site->mirror_1 ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Mirror URL 2 (facultatif)</label>
                <input type="url" name="mirror_2" class="form-control" value="{{ old('mirror_2', $site->mirror_2 ?? '') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $site->description }}</textarea>
            </div>
            <div class="mb-3">
            <label>Current Logo / Avatar</label><br>
            <img src="{{ $site->logoOrAvatarUrl() }}" width="120" alt="{{ $site->name }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="file" name="logo" class="form-control">
            </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Choisir une sous-catégorie --</option>
                        @foreach($categories as $parent)
                        <optgroup label="{{ $parent->name }}">
                            @foreach($parent->children as $child)
                            <option value="{{ $child->id }}"
                                @selected(old('category_id', $site->category_id ?? '') == $child->id)>
                                └ {{ $child->name }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags (séparés par des virgules)</label>
                <input type="text" name="tags" class="form-control"
                        value="{{ old('tags', isset($site) ? $site->tags->pluck('name')->implode(', ') : '') }}">
                <small class="text-muted">Ex : dex, cross-chain, aggregator</small>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="featured" class="form-check-input" id="featured"
                        @checked(old('featured', $site->featured ?? false))>
                <label for="featured" class="form-check-label">Mark as Featured</label>
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" name="featured_home" id="featured_home"
                        @checked(old('featured_home', $site->featured_home ?? false))>
                <label class="form-check-label" for="featured_home">Show on homepage</label>
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" name="featured_category" id="featured_category"
                        @checked(old('featured_category', $site->featured_category ?? false))>
                <label class="form-check-label" for="featured_category">Show in category</label>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="featured_tag" id="featured_tag"
                        @checked(old('featured_tag', $site->featured_tag ?? false))>
                <label class="form-check-label" for="featured_tag">Show in tag pages</label>
            </div>

            <h5 class="mt-4 mb-2">🔐 Privacy & Crypto Options</h5>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="no_kyc" id="no_kyc" {{ old('no_kyc', $site->no_kyc ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="no_kyc">No KYC Required</label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="has_onion" id="has_onion" {{ old('has_onion', $site->has_onion ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="has_onion">Has Onion Mirror (.onion)</label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="decentralized" id="decentralized" {{ old('decentralized', $site->decentralized ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="decentralized">Decentralized</label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="smart_contract" id="smart_contract" {{ old('smart_contract', $site->smart_contract ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="smart_contract">Uses Smart Contract</label>
            </div>

            <div class="col-md-6">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="scam">Scam</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
@endsection
