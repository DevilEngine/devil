@extends('layout.app')

@section('title', '✏️ Edit Site: '.$site->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h4 class="mb-4">✏️ Edit Site: {{ $site->name }}</h4>
            <hr>

            <form method="POST" action="{{ route('sites.update', $site) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">🏷️ Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $site->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🔗 URL</label>
                    <input type="url" name="url" class="form-control" value="{{ old('url', $site->url) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🧅 Mirror 1 (.onion optional)</label>
                    <input type="url" name="mirror_1" class="form-control" value="{{ old('mirror_1', $site->mirror_1) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">🌐 Mirror 2</label>
                    <input type="url" name="mirror_2" class="form-control" value="{{ old('mirror_2', $site->mirror_2) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">📝 Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $site->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">📁 Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select sub-category --</option>
                        @foreach($categories as $cat)
                            <optgroup label="📂 {{ $cat->name }}">
                                @foreach($cat->children as $sub)
                                    <option value="{{ $sub->id }}" {{ $site->category_id === $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">🏷️ Tags (comma separated)</label>
                    <input type="text" name="tags" class="form-control"
                        value="{{ old('tags', $site->tags->pluck('name')->implode(',')) }}">
                </div>

                @if(auth()->user()->free_feature_home > 0 && !$site->featured)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="use_feature_bonus" id="use_feature_bonus">
                        <label class="form-check-label" for="use_feature_bonus">
                            ⭐ Use 1 free homepage feature slot ({{ auth()->user()->free_feature_home }} left)
                        </label>
                    </div>
                @endif

                @if(auth()->user()->free_banner_slots > 0)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="use_banner_slot" id="use_banner_slot">
                        <label class="form-check-label" for="use_banner_slot">
                            📢 Use 1 free banner slot ({{ auth()->user()->free_banner_slots }} available)
                            <br><small class="text-muted">Your site will be featured at the top for 7 days.</small>
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">🖼 Banner Image (1200×300 recommended)</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*">
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">🖼 Logo (JPG/PNG – optional)</label>
                    @if($site->logo_path)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $site->logo_path) }}" alt="Logo" width="100" class="rounded border">
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control">
                </div>

                <p>Confirm form by clicking on the cut circle:</p>
                <div class="captcha-container mb-4">
                    <input type="image"
                        src="{{ $captcha['image'] }}"
                        name="captcha"
                        alt="captcha"
                        title="Click on cut circle">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
