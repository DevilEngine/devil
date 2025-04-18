@extends('layout.app')

@section('title', '⭐ My Favorite Sites')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-0">My Favorite Sites</h4>
                <hr>
                @if($sites->count())
                    <div class="row">
                    @foreach($sites as $site)
                        @include('components.site-card', ['site' => $site])
                    @endforeach
                    </div>
                    {{ $sites->links() }}
                @else
                    <div class="alert alert-info">You have not added any favorites yet.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
