@extends('layout.app')

@section('title', '🎁 My Rewards')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-0">🎁 My Rewards</h4>
                <hr>
                <h4 class="text-center">You have <strong>{{ $user->devilCoins() }} 👿</strong> <a href="{{ route('devilcoins.buy') }}" class="btn btn-outline-success">💰 Get more DevilCoins</a></h4>
                <h5>✅ Unlocked Rewards</h5>
                @if($user->reputation - $user->coins_spent >= 150)
                    <div class="mt-3 mb-3">
                        <p class="mb-1">🎖 Special Badges</p>
                        @if($user->reputation >= 150)
                        <span class="badge bg-info text-white me-1">📊 Site Analyst</span>
                        @endif
                        @if($user->reputation >= 200)
                        <span class="badge bg-danger text-white me-1">👹 Demonic Elite</span>
                        @endif
                        @if($user->reputation >= 500)
                            <span class="badge bg-success text-white me-1">🛡 Trusted User</span>
                        @endif
                    </div>
                @endif
                @if(count($unlocked))
                    <ul class="list-group mb-4">
                    @foreach($unlocked as $reward)
                        <li class="list-group-item d-flex align-items-center">
                        {{ $reward['label'] }}
                        <span class="badge bg-success ms-auto">Unlocked</span>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <p class="text-muted">No rewards yet. Keep earning DevilCoins!</p>
                @endif

                <h5>🔒 Upcoming Rewards</h5>
                @if(count($next))
                    <ul class="list-group">
                    @foreach($next as $reward)
                        <li class="list-group-item d-flex align-items-center">
                        {{ $reward['label'] }}
                        <span class="badge bg-secondary ms-auto">at {{ $reward['at'] }} DevilCoins</span>
                        </li>
                    @endforeach
                    </ul>
                @else
                    <p class="text-muted">You have unlocked all rewards. You’re a legend 😈</p>
                @endif

                <hr class="my-4">

                <h5>📜 Used Rewards</h5>
                @if($user->rewardUsages->count())
                <ul class="list-group">
                    @foreach($user->rewardUsages as $usage)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $usage->label }}</span>
                        <small class="text-muted">{{ $usage->created_at->format('Y-m-d H:i') }}</small>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted">You haven't used any rewards yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
