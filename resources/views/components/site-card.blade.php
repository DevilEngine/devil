<div class="col-md-3 mb-4">
    <div class="card h-100 position-relative {{ $site->featured ? 'featured-card' : '' }}">
        <img src="{{ $site->logoOrAvatarUrl() }}"
            class="card-img-top p-3 mt-4"
            style="height: 100px; object-fit: contain;"
            alt="{{ $site->name }}">

            <div class="card-body text-center">
                <div class="position-absolute top-0 start-0 p-2 d-flex flex-wrap gap-1 z-1">
                    @if($site->no_kyc)
                        <span class="badge bg-dark">🔒 No KYC</span>
                    @endif
                    @if($site->has_onion)
                        <span class="badge bg-secondary">🧅 Onion</span>
                    @endif
                    @if($site->decentralized)
                        <span class="badge bg-success">🧬 Decentralized</span>
                    @endif
                    @if($site->smart_contract)
                        <span class="badge bg-info text-dark">💻 Smart Contract</span>
                    @endif
                </div>
                <h5 class="card-title">{{ $site->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($site->description, 50) }}</p>
                {{-- ⭐ Note moyenne --}}
                @php
                    $avg = $site->reviews->avg('rating');
                    $count = $site->reviews->count();
                @endphp

                @if($count)
                    <div class="small text-warning mb-2">
                    ⭐ {{ number_format($avg, 1) }}/5
                    <span class="text-muted">({{ $count }} {{ Str::plural('rating', $count) }})</span>
                    </div>
                @else
                    <div class="small text-muted mb-2">Not rating</div>
                @endif
                @if($site->trustVotes->count())
                    <span class="badge bg-{{ $site->trustScore() >= 60 ? 'success' : ($site->trustScore() >= 30 ? 'warning text-dark' : 'danger') }} mb-2">
                        Trust: {{ $site->trustScore() }}%
                    </span>
                @else
                    <span class="badge bg-light text-dark border mb-2">
                        🕵️ No trust votes yet
                    </span>
                @endif

                {{-- Tags --}}
                @if($site->tags->count())
                    <div class="mb-2">
                    @foreach($site->tags as $tag)
                        <a href="{{ route('tag.show', $tag->slug) }}" class="badge bg-secondary text-decoration-none">
                        {{ $tag->name }}
                        </a>
                    @endforeach
                    </div>
                @endif
                @auth
                    <form action="{{ route('favorites.toggle', $site) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-warning btn-sm">
                        {{ auth()->user()->hasFavorited($site) ? '★ In favorites' : '☆ Add to favorites' }}
                        </button>
                    </form>
                @endauth
                <p class="d-flex inline-flex justify-content-center">
                    <a href="{{ route('site.show', $site->slug) }}" class="btn btn-sm btn-success mt-2 me-2"><img src="{{ asset('img/icon/eye-solid.png') }}" width="18" /></a>
                    <a href="{{ $site->url }}" target="_blank" class="btn btn-sm btn-success mt-2"><img src="{{ asset('img/icon/arrow-up-right-from-square-solid.png') }}" width="18" /></a>
                </p>
            </div>
    </div>
</div>