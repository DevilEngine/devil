@extends('layout.app')

@section('title', '💸 Complete Your Purchase')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4>💸 Complete Your Purchase</h4>
                <hr>
                <div class="card">
                    <div class="card-body">
                    <p><strong>DevilCoins:</strong> {{ $purchase->amount }}</p>
                    <p><strong>Price:</strong> {{ $purchase->price }} XMR</p>
                    <p><strong>Status:</strong>
                        <span class="badge bg-{{ $purchase->status === 'confirmed' ? 'success' : ($purchase->status === 'pending' ? 'warning text-dark' : 'secondary') }}">
                        {{ ucfirst($purchase->status) }}
                        </span>
                    </p>

                    @if($purchase->invoice_url && $purchase->status === 'pending')
                        <a href="{{ $purchase->invoice_url }}" class="btn btn-success" target="_blank">
                        🧾 Go to Payment Page
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
