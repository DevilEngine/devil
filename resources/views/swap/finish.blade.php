{{-- resources/views/swap.blade.php --}}
@extends('layout.app')

@section('title', 'Start 🚀')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm bg-custom">
                <div class="card-header text-center bg-success text-white">
                    <h3 class="text-white mb-0 text-center">
                        @if($swap_transaction->status == "finished")
                            <span class="badge bg-dark">
                                <img src="{{ asset('img/icon/check-solid.png') }}"  width="18" />
                            </span> 
                        @endif
                        @if($swap_transaction->status == "failed")
                            <span class="badge bg-danger">
                                <img src="{{ asset('img/icon/xmark-solid.png') }}"  width="18" />
                            </span> 
                        @endif
                        @if($swap_transaction->status == "expired")
                            <span class="badge bg-info">
                                <img src="{{ asset('img/icon/clock-solid.png') }}"  width="18" />
                            </span> 
                        @endif
                        @if($swap_transaction->status == "refunded")
                            <span class="badge bg-primary">
                                <img src="{{ asset('img/icon/money-bill-transfer-solid.png') }}"  width="18" />
                            </span> 
                        @endif
                    {{ ucfirst($swap_transaction->status) }}</h3>
                </div>
                <div class="card-body">
                    @if($swap_transaction->status == "finished")
                        <div class="text-center">
                            <p class="mb-0">You send</p>
                            <h3>{{ $exchange['deposit']['amount'] }} {{ strtoupper($swap_transaction->fromCurrency) }}</h3>
                            <p>Tx Hash <a href="{{ $swap_transaction->tx_explorer_url_send }}" class="text-decoration-none text-primary fw-bolder">{{ $swap_transaction->tx_hash_send }}</a></p>
                            <p class="mb-0">You get</p>
                            <h3>{{ $exchange['withdrawal']['amount'] }} {{ strtoupper($swap_transaction->toCurrency) }}</h3>
                            <p>Tx Hash <a href="{{ $swap_transaction->tx_explorer_url_receive }}" class="text-decoration-none text-primary fw-bolder">{{ $swap_transaction->tx_hash_receive }}</a></p>
                        </div>
                        <div class="alert alert-primary">You're a finished this swap, you can <a class="text-decoration-none fw-bolder text-white" href="{{ route('swap.start') }}">start a new swap</a>.</div>
                    @endif
                    @if($swap_transaction->status == "expired")
                        <div class="text-center">
                            <h3 class="fw-bolder mb-2">Your swap has expired. Please create a new swap if you still wish to exchange.</h3>
                            <a href="{{ route('swap.start') }}" class="btn btn-success mt-2"><img src="{{ asset('img/icon/arrow-right-arrow-left-solid.png') }}" width="18" /> Start a new swap</a>
                        </div>
                    @endif
                    @if($swap_transaction->status == "failed")
                        <div class="text-center">
                            <h3 class="fw-bolder mb-2">Something may have happened with the exchange, please contact support.</h3>
                            <a href="{{ route('user.ticket.create') }}" class="btn btn-success mt-2"><img src="{{ asset('img/icon/life-ring-solid.png') }}" width="18" /> Open a ticket support</a>
                        </div>
                    @endif
                    @if($swap_transaction->status == "refunded")
                        <div class="text-center">
                            <h3 class="fw-bolder mb-2">Your deposit has been refunded to: {{ $swap_transaction->refund_address }}</h3>
                            <a href="{{ route('swap.start') }}" class="btn btn-success mt-2"><img src="{{ asset('img/icon/arrow-right-arrow-left-solid.png') }}" width="18" /> Start a new swap</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
