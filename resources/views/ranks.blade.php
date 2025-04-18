@extends('layout.app')

@section('title', 'User Ranks & DevilCoin Levels')

@section('content')
<div class="container py-5">
  <h1 class="mb-4">🎖️ User Ranks & Progression</h1>

  <p class="mb-4">
    The more <strong>DevilCoins</strong> you earn, the higher your rank. Each rank comes with its own prestige and might unlock features over time.
  </p>

  <div class="row g-4">

    <div class="col-md-6">
      <div class="card border-secondary h-100">
        <div class="card-body">
          <h5 class="card-title">🪙 New Soul</h5>
          <p class="text-muted mb-0">0 – 19 DevilCoins</p>
          <span class="badge bg-secondary mt-2">bg-secondary</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-warning h-100">
        <div class="card-body">
          <h5 class="card-title">🔥 Infernal Bronze</h5>
          <p class="text-muted mb-0">20 – 49 DevilCoins</p>
          <span class="badge badge-bronze mt-2">badge-bronze</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-secondary h-100">
        <div class="card-body">
          <h5 class="card-title">💀 Hellfire Silver</h5>
          <p class="text-muted mb-0">50 – 99 DevilCoins</p>
          <span class="badge bg-secondary mt-2">bg-secondary</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-warning h-100">
        <div class="card-body">
          <h5 class="card-title">👹 Demonic Gold</h5>
          <p class="text-muted mb-0">100 – 199 DevilCoins</p>
          <span class="badge bg-warning mt-2">bg-warning</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-danger h-100">
        <div class="card-body">
          <h5 class="card-title">👑 Lord of the Abyss</h5>
          <p class="text-muted mb-0">200 – 499 DevilCoins</p>
          <span class="badge bg-danger mt-2">bg-danger</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-info h-100">
        <div class="card-body">
          <h5 class="card-title">🛡 Trusted Soul</h5>
          <p class="text-muted mb-0">500 – 999 DevilCoins</p>
          <span class="badge bg-info mt-2">bg-info</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-purple h-100">
        <div class="card-body">
          <h5 class="card-title">💎 Abyss Lord</h5>
          <p class="text-muted mb-0">1,000 – 1,999 DevilCoins</p>
          <span class="badge badge-purple mt-2">badge-purple</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-danger h-100">
        <div class="card-body">
          <h5 class="card-title">🐉 Archfiend</h5>
          <p class="text-muted mb-0">2,000 – 2,999 DevilCoins</p>
          <span class="badge badge-animated-red mt-2">badge-animated-red</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-dark h-100">
        <div class="card-body">
          <h5 class="card-title">🕷 Abyss Warden</h5>
          <p class="text-muted mb-0">3,000 – 4,999 DevilCoins</p>
          <span class="badge badge-animated-dark mt-2">badge-animated-dark</span>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-king h-100">
        <div class="card-body">
          <h5 class="card-title">👑 Devil King</h5>
          <p class="text-muted mb-0">5,000+ DevilCoins</p>
          <span class="badge badge-king mt-2">badge-king</span>
        </div>
      </div>
    </div>

  </div>

  <hr class="my-5">

  <p class="mb-0">
    You earn DevilCoins by contributing to the platform: writing reviews, gaining trust votes, submitting quality content, or achieving community goals.
  </p>
</div>
@endsection
