@extends('layout.app')

@section('title', '📁 '.$category->name)
@section('meta_description', "Discover trusted websites in the category $category->name. Browse platforms reviewed by the community and ranked by trust score on Devil Engine.")

@section('content')
    <div class="container mt-4">
        <h4 class="mb-0">📁 {{ $category->name }}</h4>
        @if($category->description)
            <p class="text-muted">📝 {{ $category->description }}</p>
        @endif
        <hr>

        @if($featuredSites->count())
            <h4 class="mb-3">🌟 Featured Sites</h4>
            <div class="row mb-4">
                @foreach($featuredSites as $site)
                    @include('components.site-card', ['site' => $site])
                @endforeach
            </div>
        @endif

        {{-- 🔎 Filter Form --}}
        <form method="GET" class="row g-3 mb-4">
            {{-- Tag filter --}}
            <div class="col-md-4">
                <select name="tag" class="form-select">
                    <option value="">🏷️ All tags</option>
                    @foreach($tags as $t)
                        <option value="{{ $t }}" @selected(request('tag') == $t)>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status filter --}}
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">⚙️ All statuses</option>
                    <option value="active" @selected(request('status') == 'actif')>✅ Active</option>
                    <option value="inactive" @selected(request('status') == 'inactif')>⏸️ Inactive</option>
                    <option value="scam" @selected(request('status') == 'scam')>🚨 Scam / Suspicious</option>
                </select>
            </div>

            {{-- Darknet filter --}}
            <div class="col-md-3">
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
                <button class="btn btn-success">🔍 Filter</button>
            </div>
        </form>

        {{-- 🔽 Results --}}
        @if($pagedSites->count())
            <div class="row">
                @foreach($pagedSites as $site)
                    @include('components.site-card', ['site' => $site])
                @endforeach
            </div>

            {{ $pagedSites->links() }}
        @else
            <div class="alert alert-warning">😕 No website found in this sub category.</div>
        @endif
    </div>
@endsection
