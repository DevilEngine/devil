@extends('layout.app')

@section('title', '💰 Buy DevilCoins')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4>💰 Buy DevilCoins</h4>
                <hr>
                <p class="text-muted">Choose your pack and pay with Monero (XMR). Your DEVC balance will be updated once the transaction is confirmed.</p>

                <div class="row g-4">
                @foreach($packs as $pack)
                    <div class="col-md-6 col-lg-4">
                        <div class="card text-center shadow-sm border-success h-100">
                        <div class="card-body">
                            <h4 class="card-title">{{ $pack->amount }} <span class="text-success">DEVC</span></h4>
                            <p class="card-text fs-5">💸 {{ $pack->usd_price }} USD</p>
                            <form method="POST" action="{{ route('devilcoins.checkout') }}">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $pack->id }}">
                                <button class="btn btn-success">
                                    Buy Now
                                </button>
                            </form>
                        </div>
                        </div>
                    </div>
                @endforeach
                </div>
                <div class="alert alert-warning mt-4">Purchasing <strong>devilcoins</strong> requires javascript, if you want to buy without javascript you contact me here</div>
            </div>
        </div>
    </div>
@endsection
