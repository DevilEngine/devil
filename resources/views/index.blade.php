@extends('layout.app')

@section('title', '🏠 Home')

@section('content')
        <!-- Page content-->
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    @if($featuredSites->count())
                        <h2 class="mb-0 mt-4">🌟 Featured Sites</h2>
                        <hr>
                        <div class="row mb-4">
                            @foreach($featuredSites as $site)
                                @include('components.site-card', ['site' => $site])
                            @endforeach
                        </div>
                    @endif
                    @if($banners->count())
                        <div class="row mb-4">
                            @foreach($banners as $banner)
                            <div class="col-md-4">
                                <a href="{{ $banner->external_url ?? $banner->url }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="img-fluid">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @endif
                    @auth
                        @if($suggestedSites->count())
                            <div class="mt-5">
                            <h2 class="mb-4">🔥 Suggested for You</h2>

                            <div class="row g-4">
                                @foreach($suggestedSites as $site)
                                    @include('components.site-card')
                                @endforeach
                            </div>
                            </div>
                        @endif
                    @endauth

                    <div class="container mt-2">
                        <h2 class="mb-3 border-bottom pb-2">🔍 Search</h2>
                        <form action="{{ route('search') }}" method="GET" class="d-flex" role="search">
                            <div class="input-group">
                                <input class="form-control" type="search" name="q" placeholder="Search by name, category, tag..." value="{{ request('q') }}">
                                <button class="btn btn-success me-4" type="submit"><img src="{{ asset('img/icon/magnifying-glass-solid.png') }}" width="18" /></button>
                            </div>
                        </form>
                    </div>
                    @if($tags->count())
                        <div class="mb-4 mt-4 text-center">
                            @foreach($tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}"
                                class="badge bg-light text-dark text-decoration-none border me-2 mb-2"
                                style="font-size: {{ rand(0, 1) ? '0.95rem' : '1.1rem' }};">
                                {{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="container mt-5">
                        @foreach($categories as $cat)
                            <div class="mb-5">
                            <h2 class="mb-3 border-bottom pb-2">{{ $cat->name }}</h2>

                            @if($cat->children->count())
                                @foreach($cat->children as $sub)
                                <div class="mb-4">
                                <div class="card mb-4 bg-dark">
                                    <div class="card-body">
                                        <h4 class="mb-0 float-start">{{ $sub->name }}</h4><p class="mb-0 float-end" ><a href="{{ route('category.show', [$sub->parent->slug, $sub->slug]) }}" class="btn btn-success"><img src="{{ asset('img/icon/eye-solid.png') }}" width="18" /> View</a></p>
                                    </div>
                                </div>
                                    @if($sub->sites->count())
                                    <div class="row clear-both">
                                        @foreach($sub->sites->where('status', 'active')->take(8) as $site)
                                            @include('components.site-card', ['site' => $site])
                                        @endforeach
                                    </div>
                                    @else
                                        <div class="alert alert-warning text-center">No website for this category</div>
                                    @endif
                                </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning text-center">No category defined</div>
                            @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
@endsection

