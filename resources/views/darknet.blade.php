@extends('layout.app')

@section('title', 'Darknet')

@section('content')
    <div class="container">
        <h4 class="mb-4">🌑 Darknet</h4>
        <hr>
        @if($sites->count())
            <div class="row row-cols-1 g-4">
            @foreach($sites as $site)
                <div class="col">
                    @include('components.site-card', ['site' => $site])
                </div>
            @endforeach
            </div>

            <div class="mt-4">
            {{ $sites->links() }}
            </div>
        @else
            <p class="text-muted">No darknet sites found.</p>
        @endif
    </div>
@endsection
