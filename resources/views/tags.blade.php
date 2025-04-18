@extends('layout.app')

@section('title', '🏷️ Tag: '.$tag->name)
@section('meta_description', "Websites with the tag {$tag->name}")

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">🏷️ Tag : <span class="text-success">{{ $tag->name }}</span></h4>
        <hr>

        @if($featuredSites->count())
            <h4 class="mb-3">🌟 Featured Sites</h4>
            <div class="row mb-4">
                @foreach($featuredSites as $site)
                    @include('components.site-card', ['site' => $site])
                @endforeach
            </div>
        @endif

        <form method="GET" class="row g-3 mb-4">
            <input type="hidden" name="slug" value="{{ $tag->slug }}">

            {{-- Category filter --}}
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">📁 All categories</option>
                    @foreach($categories as $parent)
                        <optgroup label="{{ $parent->emoji }} {{ $parent->name }}">
                            @foreach($parent->children as $child)
                                <option value="{{ $child->id }}" @selected(request('category_id') == $child->id)>
                                    └ {{ $child->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            {{-- Status filter --}}
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">⚙️ All statuses</option>
                    <option value="actif" @selected(request('status') == 'actif')>✅ Active</option>
                    <option value="inactif" @selected(request('status') == 'inactif')>⏸️ Inactive</option>
                    <option value="scam" @selected(request('status') == 'scam')>🚨 Scam / Suspicious</option>
                </select>
            </div>

            {{-- Darknet checkbox --}}
            <div class="col-md-2">
                <div class="form-check mt-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="darknet"
                        id="darknet"
                        value="1"
                        {{ request('darknet') ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="darknet">
                        🌑 Darknet only (.onion)
                    </label>
                </div>
            </div>

            {{-- Submit button --}}
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-success">🔍 Filter</button>
            </div>
        </form>

        @if($pagedSites->count())
            <div class="row">
                @foreach($pagedSites as $site)
                    @include('components.site-card', ['site' => $site])
                @endforeach
            </div>
            {{ $pagedSites->links() }}
        @else
            <div class="alert alert-warning">😕 No website found for this tag.</div>
        @endif
    </div>
@endsection
