@extends('layout.app')

@section('title', $user->username)
@section('meta_description', "View the profile of $user->username on Devil Engine. See their reviews, reputation, and contributions to the community.")

@section('content')
    <div class="container mt-4">
        @php
            [$rankLabel, $rankClass] = $user->devilRank();
            $progress = $user->devilProgress();
        @endphp

        <div class="card shadow-lg border border-success mb-4">
            <div class="card-body d-flex align-items-center">

                <div class="me-4">
                    <img src="{{ $user->avatarUrl() }}" class="rounded-circle" width="100" height="100" alt="{{ $user->username }}">
                </div>
                
                <div class="flex-grow-1">
                <h4 class="mb-1">
                    {{ $user->username }}
                    <span class="badge {{ $rankClass }} ms-2">{{ $rankLabel }}</span>
                </h4>
                <p class="text-muted mb-2">{{ $user->bio ?? 'No bio provided.' }}</p>

                <div class="mb-2">
                    <strong>😈 {{ $user->devilCoins() }}</strong>
                    <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success"
                        role="progressbar"
                        style="width: {{ $progress['progress'] }}%;"
                        aria-valuenow="{{ $progress['progress'] }}"
                        aria-valuemin="0" aria-valuemax="100">
                    </div>
                    </div>
                    <small class="text-muted">
                    {{ $progress['current'] }} / {{ $progress['next_at'] ?? '∞' }} —
                    Next: {{ $progress['next_rank'] ?? '👑 Max Level' }}
                    </small>
                </div>
                </div>
            </div>
        </div>
        <h4 class="mt-4">📝 Recent Reviews</h4>
        @if($reviews->count())
            <ul class="list-group">
            @foreach($reviews as $review)
                <li class="list-group-item">
                <strong>{{ $review->site->name }}</strong> — {{ $review->rating }}/5<br>
                {{ Str::limit($review->content, 100) }}
                </li>
            @endforeach
            </ul>
        @else
            <p class="text-muted">No reviews yet.</p>
        @endif
        <h4 class="mt-4">📤 Submitted Sites</h4>
        @if($submittedSites->count())
            <div class="row">
                @foreach($submittedSites as $site)
                    @include('components.site-card', ['site' => $site])
                @endforeach
            </div>
        @else
            <p class="text-muted">No sites submitted yet.</p>
        @endif
    </div>
@endsection
