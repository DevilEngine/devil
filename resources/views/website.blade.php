@extends('layout.app')

@section('title', '🌐 '.$site->name)

@section('content')
    <div class="container mt-4">
        <h4 class="mb-0">{{ $site->name }}</h4>
        <hr>
        <div class="row mb-5">
            <div class="col-md-3 text-center">
                <img class="w-100 mb-3" src="{{ $site->logoOrAvatarUrl() }}" alt="{{ $site->name }}">
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <p><strong>🎯 Category :</strong> <a class="badge bg-success text-decoration-none" href="{{ route('category.show', [$site->category->parent->slug, $site->category->slug]) }}">{{ $site->category->name ?? 'N/A' }}</a></p>
                        <p><strong>🧿 Status :</strong> <span class="badge bg-success">{{ ucfirst($site->status) }}</span></p>
                        <p><strong>📝 Description :</strong> {{ $site->description }}</p>
                        <p><strong>🏷️ Tags :</strong>
                            @foreach($site->tags as $tag)
                                <a href="{{ route('tag.show', $tag->slug) }}" class="badge bg-secondary text-decoration-none">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </p>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ $site->url }}" target="_blank" class="btn btn-success mt-2">
                                    🌍 Visit site <img src="{{ asset('img/icon/arrow-up-right-from-square-solid.png') }}" width="18" />
                                </a>
                                <br>
                                @auth
                                    <div class="mt-4">
                                        <form action="{{ route('favorites.toggle', $site) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-outline-warning btn-sm">
                                                {{ auth()->user()->hasFavorited($site) ? '★ In favorites' : '☆ Add to favorites' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('site.follow', $site->slug) }}" class="d-inline">
                                            @csrf
                                            @if(auth()->user()->followedSites->contains($site->id))
                                                <button class="btn btn-sm btn-outline-danger">🚫 Unfollow</button>
                                            @else
                                                <button class="btn btn-sm btn-outline-primary">📌 Follow</button>
                                            @endif
                                        </form>
                                    </div>
                                @endauth

                                @auth
                                    <a href="{{ route('site.report.form', $site->slug) }}" class="btn btn-outline-danger btn-sm mt-3">
                                        🚩 Report this site
                                    </a>
                                @endauth

                                @auth
                                    @if($site->user_id != Auth::id() && !$site->claims()->where('user_id', auth()->id())->where('status', 'pending')->exists())
                                        <a href="{{ route('site.claim.form', $site->slug) }}" class="btn btn-outline-info btn-sm mt-3">🙋 Request Ownership</a>
                                    @endif
                                @endauth

                                @if($site->mirror_1 || $site->mirror_2)
                                    <div class="mt-3">
                                        <h6>🌐 Other access :</h6>
                                        <ul class="list-unstyled">
                                            @if($site->mirror_1)
                                                <li>
                                                    <a href="{{ $site->mirror_1 }}" target="_blank" rel="nofollow" class="link-primary">
                                                        {{ parse_url($site->mirror_1, PHP_URL_HOST) ?? $site->mirror_1 }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if($site->mirror_2)
                                                <li>
                                                    <a href="{{ $site->mirror_2 }}" target="_blank" rel="nofollow" class="link-primary">
                                                        {{ parse_url($site->mirror_2, PHP_URL_HOST) ?? $site->mirror_2 }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @php
                                $score = $site->trustScore();
                                $badgeClass = $score >= 60 ? 'bg-success' : ($score >= 30 ? 'bg-warning text-dark' : 'bg-danger');
                            @endphp
                            <div class="col-md-6">
                                @if($site->trustVotes->count())
                                    <div class="mt-2">
                                    <span class="badge {{ $badgeClass }}">
                                        🛡️ Trust Score: {{ $score }}%
                                    </span>
                                        <small class="text-muted ms-2">({{ $site->trustVotes->count() }} votes)</small>
                                    </div>
                                @else
                                    <p class="text-muted">🕵️ No trust votes yet.</p>
                                @endif

                                @auth
                                    <div class="my-3">
                                        <h5 class="mb-2">🤝 Do you trust this site?</h5>
                                        <form action="{{ route('sites.trust.vote', $site) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="trusted" value="1">
                                            <button class="btn btn-outline-success btn-sm me-2" {{ auth()->user()->trustVotes()->where('site_id', $site->id)->value('trusted') === 1 ? 'disabled' : '' }}>
                                                👍 Trust
                                            </button>
                                        </form>

                                        <form action="{{ route('sites.trust.vote', $site) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="trusted" value="0">
                                            <button class="btn btn-outline-danger btn-sm" {{ auth()->user()->trustVotes()->where('site_id', $site->id)->value('trusted') === 0 ? 'disabled' : '' }}>
                                                👎 Risky
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mt-4">🔐 Privacy & Blockchain Info</h4>
                                <ul class="list-unstyled">
                                    @if($site->no_kyc)
                                        <li><span class="badge bg-dark mb-2">🔒 No KYC Required</span></li>
                                    @endif
                                    @if($site->has_onion)
                                        <li><span class="badge bg-secondary mb-2">🧅 Has Onion Mirror</span></li>
                                    @endif
                                    @if($site->decentralized)
                                        <li><span class="badge bg-success mb-2">🧬 Decentralized Platform</span></li>
                                    @endif
                                    @if($site->smart_contract)
                                        <li><span class="badge bg-info text-dark mb-2">💻 Uses Smart Contract</span></li>
                                    @endif

                                    @unless($site->no_kyc || $site->has_onion || $site->decentralized || $site->smart_contract)
                                        <li class="text-muted">No crypto privacy indicators provided.</li>
                                    @endunless
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3">📊 Rating :
            @if($average)
                ⭐ {{ $average }}/5
            @else
                <span class="text-muted">Not rated yet</span>
            @endif
        </h4>

        <hr>

        @auth
            @if(auth()->user()?->isTrustedUser())
                <p class="text-success small">
                    🛡️ As a trusted user, your review will be published instantly.
                </p>
            @else
                <p class="text-muted small">
                    Your review will appear after moderation.
                </p>
            @endif

            <form action="{{ route('site.review', $site->slug) }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">⭐ Rating</label>
                        <select name="rating" class="form-select" required>
                            <option value="">-- Choose --</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} ⭐</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">💬 Comment (optional)</label>
                        <textarea name="comment" class="form-control" rows="2">{{ old('comment') }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <p>Confirm form by clicking on the cut circle:</p>
                        <div class="captcha-container mb-4">
                            <input type="image"
                                src="{{ $captcha['image'] }}"
                                name="captcha"
                                alt="captcha"
                                title="Click on cut circle">
                        </div>
                    </div>
                </div>
            </form>
        @endauth

        @guest
            <div class="alert alert-primary">You must be logged in to leave a review.</div>
        @endguest

        <h5 class="mt-4">🧑‍💻 User Reviews</h5>

        @forelse($site->reviews->sortByDesc('created_at') as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <a class="text-decoration-none text-white" href="{{ route('user.public', $review->user->username) }}">
                        <strong>{{ $review->user->username ?? 'Anonymous' }}</strong>
                    </a>
                    @if($review->user->isContributor())
                        <span class="badge bg-success ms-1">🏅</span>
                    @endif
                    @if($review->user->isTrustedUser())
                        <span class="badge bg-success text-white ms-1">🛡 Trusted</span>
                    @endif
                    <span class="text-warning">({{ $review->rating }} ⭐)</span><br>
                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">No rating for now.</p>
        @endforelse
    </div>
@endsection
