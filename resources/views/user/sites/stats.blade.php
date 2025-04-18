@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-4">📊 Stats for "{{ $site->name }}"</h4>
                <hr>
                <div class="row g-4">
                    <div class="col-md-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body text-center">
                        <h3>{{ $totalReviews }}</h3>
                        <p class="mb-0">Total Reviews</p>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body text-center">
                        <h3>{{ $approvedReviews }}</h3>
                        <p class="mb-0">Approved Reviews</p>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body text-center">
                        <h3>
                            @if($avgRating)
                            ⭐ {{ number_format($avgRating, 1) }}/5
                            @else
                            N/A
                            @endif
                        </h3>
                        <p class="mb-0">Average Rating</p>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-3">
                    <div class="card text-white bg-dark h-100">
                        <div class="card-body text-center">
                        <h3>{{ $followersCount }}</h3>
                        <p class="mb-0">Followers</p>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('sites.index') }}" class="btn btn-outline-secondary">← Back to My Sites</a>
                </div>
            </div>
        </div>
    </div>
@endsection
