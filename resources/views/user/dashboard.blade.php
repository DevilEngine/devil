@extends('layout.app')

@section('title', '👤 Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-4">Welcome back, {{ Auth::user()->username }}
                    @if(auth()->user()->isContributor())
                        <span class="badge bg-success align-middle ms-2">🏅 Contributor</span>
                    @endif
                    <span class="badge bg-success ms-2">😈 {{ auth()->user()->devilCoins() }}</span>
                    @php [$rankLabel, $rankClass] = auth()->user()->devilRank(); @endphp
                    <span class="badge {{ $rankClass }} ms-2">
                        {{ $rankLabel }}
                    </span>
                </h4>
                <hr>
                <div class="card">
                    <div class="card-body">
                        @php $progress = auth()->user()->devilProgress(); @endphp
            
                        <h5 class="mt-0">😈 Level Progress</h5>

                        <p>
                            {{ $progress['current'] }} / {{ $progress['next_at'] ?? '∞' }}
                            @if($progress['next_rank'])
                                → Next Rank: <strong>{{ $progress['next_rank'] }}</strong>
                            @endif
                        </p>

                        <div class="progress" style="height: 20px;">
                            <div
                                class="progress-bar bg-danger"
                                role="progressbar"
                                style="width: {{ $progress['progress'] }}%;"
                                aria-valuenow="{{ $progress['progress'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            >
                                {{ $progress['progress'] }}%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        {{-- FAVORITES --}}
                        <h2 class="h4 mb-4">⭐ Your Favorites</h2>
                        @if($favorites->count())
                            <div class="row mb-3">
                            @foreach($favorites as $site)
                                @include('components.site-card', ['site' => $site])
                            @endforeach
                            </div>
                            <a href="{{ route('favorites.index') }}" class="btn btn-sm btn-outline-secondary">View all favorites</a>
                        @else
                            <p class="text-muted">You haven't added any favorites yet.</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        {{-- REVIEWS --}}
                        <h2 class="h4 mb-4">📝 Your Reviews</h2>
                        @if($reviews->count())
                            <ul class="list-group mb-3">
                            @foreach($reviews as $review)
                                <li class="list-group-item">
                                <strong>{{ $review->site->name }}</strong> — {{ $review->rating }}/5<br>
                                {{ Str::limit($review->content, 100) }}
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p class="text-muted">You haven't written any reviews yet.</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        {{-- SUBMISSIONS --}}
                        <h2 class="h4 mb-4">📤 Your Submitted Sites</h2>
                        @if($submittedSites->count())
                            <ul class="list-group mb-3">
                            @foreach($submittedSites as $site)
                                <li class="list-group-item">
                                <strong>{{ $site->name }}</strong>
                                <span class="badge bg-{{ $site->status === 'active' ? 'success' : 'warning' }} float-end">
                                    {{ ucfirst($site->status) }}
                                </span>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p class="text-muted">You haven't submitted any sites yet.</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-4 mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-4">👍👎 Your Trust Votes</h2>

                        @if($trustVotes->count())
                        <ul class="list-group mb-4">
                            @foreach($trustVotes as $vote)
                            <li class="list-group-item">
                                <strong>{{ $vote->site->name }}</strong> —
                                @if($vote->trusted)
                                <span class="text-success">👍 Trusted</span>
                                @else
                                <span class="text-danger">👎 Risky</span>
                                @endif
                                <small class="text-muted ms-2">({{ $vote->created_at->format('Y-m-d') }})</small>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-muted">You haven't voted on any site yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
