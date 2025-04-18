@extends('layout.app')

@section('title', 'Leaderboard')

@section('content')
<div class="container mt-4">
  <h4 class="mb-4">🏆 Top Contributors</h4>
  <hr>

  @if($topUsers->isEmpty())
    <p class="text-muted">No active contributors yet.</p>
  @else
    <div class="row g-4">
    @foreach($topUsers as $index => $user)
    @php
      [$rankLabel, $rankClass] = $user->devilRank();
      $position = $index + 1;

      // Style spécifique pour les 3 premiers
      $border = match($index) {
        0 => 'warning',
        1 => 'secondary',
        2 => 'danger',
        default => 'success'
      };

      $medal = match($index) {
        0 => '🥇',
        1 => '🥈',
        2 => '🥉',
        default => ''
      };
    @endphp

    <div class="col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm border border-3 border-{{ $border }}">
        <div class="card-body d-flex">
          <img src="{{ $user->avatarUrl() }}"
               class="rounded-circle me-3 border border-dark"
               width="64" height="64"
               alt="{{ $user->username }}">

          <div class="flex-grow-1">
            <h5 class="mb-1">
              {{ $medal }} {{ $user->username }}
              @if($user->isContributor())
                <span class="badge bg-success ms-1">🏅</span>
              @endif
            </h5>

            <div class="mb-1">
              <span class="badge {{ $rankClass }}">{{ $rankLabel }}</span>
              @if($user->isTrustedUser())
                <span class="badge bg-success ms-1">🛡</span>
              @endif
            </div>

            <div class="small text-muted mb-2">
              👿 {{ $user->devilCoins() }} &nbsp;•&nbsp;
              🏆 <strong>Rank #{{ $position }}</strong>
            </div>

            <a href="{{ route('user.public', $user->slug) }}"
               class="btn btn-sm btn-outline-{{ $border }}">
              👁 View Profile
            </a>
          </div>
        </div>
      </div>
    </div>
  @endforeach
    </div>
  @endif
</div>
@endsection
