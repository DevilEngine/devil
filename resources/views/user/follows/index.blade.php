@extends('layout.app')

@section('title', '📌 Followed Sites')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-4">📌 Followed Sites</h4>
                <hr>
                @if($sites->count())
                    <div class="row">
                    @foreach($sites as $site)
                        <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('site.show', $site->slug) }}">{{ $site->name }}</a>
                            </h5>
                            <p class="card-text text-muted">
                                Category: {{ $site->category->name ?? '-' }}
                            </p>

                            <form method="POST" action="{{ route('site.follow', $site->slug) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">🚫 Unfollow</button>
                            </form>
                            </div>
                        </div>
                        </div>
                    @endforeach
                    </div>
                @else
                    <p class="text-muted">You’re not following any sites yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
