@extends('layout.app')

@section('title', '⭐ Promote your site')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h4 class="mb-3">⭐ Promote your site: <strong>{{ $site->name }}</strong></h4>
            <hr>

            <p class="text-muted mb-4">
                Choose where you want your site to appear for 7 days. Each visibility option has a DevilCoin cost. 
                Current available: <strong>👿 {{ auth()->user()->availableDevilCoins() }} DEVC</strong>
            </p>

            @if(!$site->isCurrentlyFeatured() && auth()->user()->availableDevilCoins() >= 75)
            <form action="{{ route('sites.feature.multi', $site) }}" method="POST" class="border p-4 rounded">
                @csrf

                <p class="fw-bold mb-3">💎 Select your promotion zones:</p>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="home" id="home">
                    <label class="form-check-label" for="home">
                        🌐 Homepage (150 DEVC)
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="category" id="category">
                    <label class="form-check-label" for="category">
                        📁 Category Page (100 DEVC)
                    </label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="tag" id="tag">
                    <label class="form-check-label" for="tag">
                        🏷️ Tag Pages (75 DEVC)
                    </label>
                </div>

                <p>Confirm form by clicking on the cut circle:</p>
                <div class="captcha-container mb-4">
                    <input type="image"
                    src="{{ $captcha['image'] }}"
                    name="captcha"
                    alt="captcha"
                    title="Click on cut circle">
                </div>
            </form>
            @else
                <div class="alert alert-info">
                    This site is already featured, or you don’t have enough DevilCoins.
                </div>
            @endif

            @if($site->isCurrentlyFeatured())
            <div class="mt-4">
                <h6>✅ Active Promotions:</h6>
                <ul class="list-group">
                    @if($site->featured_home && $site->featured_home_until)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🌐 Homepage
                            <span class="badge bg-success">
                                Until {{ $site->featured_home_until->format('Y-m-d H:i') }}
                            </span>
                        </li>
                    @endif
                    @if($site->featured_category && $site->feature_category_until)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            📁 Category
                            <span class="badge bg-success">
                                Until {{ $site->feature_category_until->format('Y-m-d H:i') }}
                            </span>
                        </li>
                    @endif
                    @if($site->featured_tag && $site->feature_tag_until)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            🏷️ Tag Pages
                            <span class="badge bg-success">
                                Until {{ $site->feature_tag_until->format('Y-m-d H:i') }}
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
