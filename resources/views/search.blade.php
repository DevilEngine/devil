@extends('layout.app')

@section('title', '🔍 Search filter')
@section('meta_description', "Browse websites matching $query on Devil Engine. Filter by category, tags, or darknet status to find the best results.")

@section('content')
    <div class="container mt-4">
        <h4 class="mb-0">🔎 Search Results</h4>
        <hr>

        @if($query)
            <p class="text-muted">📌 You searched for: <strong>{{ $query }}</strong></p>

            <form action="{{ route('search') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="🔍 Keyword...">
                </div>

                <div class="col-md-3">
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

                <div class="col-md-3">
                    <select name="tag" class="form-select">
                        <option value="">🏷️ All tags</option>
                        @foreach($tags as $t)
                            <option value="{{ $t }}" @selected(request('tag') == $t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">⚙️ All statuses</option>
                        <option value="active" @selected(request('status') == 'actif')>✅ Active</option>
                        <option value="inactive" @selected(request('status') == 'inactif')>⏸️ Inactive</option>
                        <option value="scam" @selected(request('status') == 'scam')>🚨 Scam / Suspicious</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="darknet" name="darknet" {{ request('darknet') ? 'checked' : '' }}>
                        <label class="form-check-label" for="darknet">
                            🌑 Darknet only (.onion)
                        </label>
                    </div>
                </div>

                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-success">
                        🔍 Filter
                    </button>
                </div>
            </form>

            @if($pagedSites->isEmpty())
                <div class="alert alert-warning">😕 No results found.</div>
            @else
                <div class="row">
                    @foreach($pagedSites as $site)
                        @include('components.site-card', ['site' => $site])
                    @endforeach
                </div>
                <div>{{ $pagedSites->links() }}</div>
            @endif
        @else
            <div class="alert alert-info">🔎 Start by typing a keyword above.</div>
        @endif
    </div>
@endsection
