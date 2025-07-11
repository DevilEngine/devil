@extends('layout.app')

@section('title', '💸 Withdraw DevilCoins')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>
        <div class="col-md-9">
            <h4 class="mb-0">💸 Withdraw DevilCoins</h4>
            <hr>
            <div class="alert alert-info">
                👿 You currently have <strong>{{ auth()->user()->availableDevilCoins() }}</strong> DevilCoins.
            </div>

            <form method="POST" action="{{ route('user.withdrawals.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">💰 Amount to withdraw (DevilCoins)</label>
                    <input type="number" name="amount" class="form-control" required min="1000" step="1" placeholder="e.g 1000">
                </div>

                <p class="text-muted">
                    📈 Exchange rate: 100 DevilCoins = <strong>0.01 XMR</strong><br>
                    👉 Example: 1000 DevilCoins ≈ <strong>0.1 XMR</strong>
                </p>

                <div class="mb-3">
                    <label class="form-label">Monero address</label>
                    <input type="text" name="xmr_address" class="form-control" required
                        value="{{ old('xmr_address') }}">
                    @error('xmr_address') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="form-text text-muted mb-3">
                    💡 Withdrawals are processed manually. Please allow 24-48 hours.
                </div>

                <div class="alert alert-info">We reserve the right not to accept withdrawals; a note will then be indicated with the reason for refusal.</div>

                <p>Confirm form by clicking on the cut circle:</p>
                <div class="captcha-container mb-4">
                    <input type="image"
                        src="{{ $captcha['image'] }}"
                        name="captcha"
                        alt="captcha"
                        title="Click on cut circle">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
