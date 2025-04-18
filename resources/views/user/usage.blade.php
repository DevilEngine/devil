@extends('layout.app')

@section('title', '🧾 DevilCoin Usage History')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-4">🧾 DevilCoin Usage History</h4>
                <hr>
                <div class="alert alert-success border d-flex justify-content-between">
                    <div>
                        <strong>Total Earned:</strong> {{ auth()->user()->earnedDevilCoins() }} DEVC<br>
                        <strong>Spent:</strong> {{ auth()->user()->coins_spent }} DEVC
                    </div>
                    <div class="fs-4 fw-bold align-self-center">
                        = {{ auth()->user()->devilCoins() }}
                    </div>
                </div>
                <div class="alert alert-warning">Purchasing a banner or feature with your devilcoins will not impact your progress on the site.</div>
                @if($usages->isEmpty())
                    <p class="text-muted">You haven't spent any DevilCoins yet.</p>
                @else
                    <ul class="list-group">
                    @foreach($usages as $usage)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $usage->description }}<br>
                            <small class="text-muted">{{ $usage->created_at->format('Y-m-d H:i') }}</small>
                        </div>
                        <span class="badge bg-danger rounded-pill">-{{ $usage->amount }} DEVC</span>
                        </li>
                    @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
