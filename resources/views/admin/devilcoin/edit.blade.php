@extends('layout.admin')

@section('title','✏️ Edit Package')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">✏️ Edit DevilCoin Package</h1>

        <form method="POST" action="{{ route('devilcoin-packages.update', $package->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
            <label class="form-label">DevilCoins</label>
            <input type="number" name="amount" class="form-control" required min="1"
                    value="{{ old('amount', $package->amount ?? '') }}">
            </div>

            <div class="mb-3">
            <label class="form-label">USD Price</label>
            <input type="number" step="0.000001" name="usd_price" class="form-control" required
                    value="{{ old('usd_price', $package->usd_price ?? '') }}">
            </div>

            <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" id="active"
                    {{ old('active', $package->active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">
                Active (visible for users)
            </label>
            </div>

            <button class="btn btn-success">Save</button>
            <a href="{{ route('devilcoin-packages.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
@endsection
