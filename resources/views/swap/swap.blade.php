{{-- resources/views/swap.blade.php --}}
@extends('layout.app')

@section('title', 'Swap 🚀')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm bg-custom">
                <div class="card-header text-center bg-success text-white">
                    <h4 class="mb-0">Swap 🚀</h4>
                </div>
                <div class="card-body">
                    <p class="fs-3 text-center mb-0"><strong>{{ $swap_transaction->token }}</strong> | 
                        @if($swap_transaction['status'] == "waiting" || $swap_transaction->status == "exchanging" || $swap_transaction->status == "sending" || $swap_transaction->status == "verifying" || $swap_transaction->status == "confirming")
                            <span class="badge bg-primary">
                        @endif
                        @if($swap_transaction->status == "finished")
                            <span class="badge bg-success">
                        @endif
                        @if($swap_transaction->status == "fail" || $swap_transaction->status == "expired" || $swap_transaction->status == "refunded")
                            <span class="badge bg-danger">
                        @endif
                            {{ ucfirst($swap_transaction->status) }}
                        </span>
                    </p>
                    <hr>
                    @if($swap_transaction->status == "waiting")
                        <div class="row">
                            <div class="col-lg-7">
                                <p class="fs-3 fw-bolder">Deposit</p>
                                <p class="fs-5">Send:<br><strong>{{ $swap_transaction->expect_amount_to_send }} {{ strtoupper($swap_transaction->fromCurrency) }}</strong></p>
                                <p class="fs-5">To:<br><strong><span class="auto-select">{{ $swap_transaction->address_send }}</span></strong></p>
                                @if($swap_transaction->tx_hash_send != null)
                                    <p class="fs-5">Tx Hash:<br><a class="text-primary text-decoration-none" href="{{ $swap_transaction->tx_explorer_url_send }}"><strong>{{ $swap_transaction->tx_hash_send }}</strong></a></p>
                                @endif
                            </div>
                            <div class="col-lg-5 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ asset('img/qr_code/'.$swap_transaction->qr_code) }}" width="200" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p class="fs-3 fw-bolder">Receive</p>
                        <p class="fs-5">Get:<br><strong>≈{{ $swap_transaction->expect_amount_to_receive }} {{ strtoupper($swap_transaction->toCurrency) }}</strong></p>
                        <p class="fs-5">Address:<br><strong>{{ $swap_transaction->address_receive }}</strong></p>
                        @if($swap_transaction->tx_hash_receive != null)
                            <p class="fs-5">Tx Hash:<br><a class="text-primary text-decoration-none" href="{{ $swap_transaction->tx_explorer_url_receive }}"><strong>{{ $swap_transaction->tx_hash_receive }}</strong></a></p>
                        @endif
                        <div class="alert alert-primary">You have 24 hours to send the funds, otherwise the swap will be considered expired, you will then have to open another swap.</div>
                    @endif
                    @if($swap_transaction->status == "exchanging")
                        <p class="mb-0 fs-5 text-center">Your swap is in progress. Exchanging coins..</p>
                        @if($swap_transaction->tx_hash_send != null)
                            <p class="fs-5">Tx Hash:<br><a class="text-primary text-decoration-none" href="{{ $swap_transaction->tx_explorer_url_send }}"><strong>{{ $swap_transaction->tx_hash_send }}</strong></a></p>
                        @endif
                    @endif
                    @if($swap_transaction->status == "sending")
                        <p class="mb-0 fs-5 text-center">Your swap is almost complete. Sending your funds now...</p>
                        @if($swap_transaction->tx_hash_receive != null)
                            <p class="fs-5">Tx Hash:<br><a class="text-primary text-decoration-none" href="{{ $swap_transaction->tx_explorer_url_receive }}"><strong>{{ $swap_transaction->tx_hash_receive }}</strong></a></p>
                        @endif
                    @endif
                    @if($swap_transaction->status == "verifying" || $swap_transaction->status == "confirming")
                        <p class="mb-0 fs-5 text-center">Your deposit has been received and is confirming on the network.</p>
                        @if($swap_transaction->tx_hash_send != null)
                            <p class="fs-5">Tx Hash send :<br><a class="text-primary text-decoration-none" href="{{ $swap_transaction->tx_explorer_url_send }}"><strong>{{ $swap_transaction->tx_hash_send }}</strong></a></p>
                        @endif
                        @if($swap_transaction->tx_hash_receive != null)
                            <p class="fs-5">Tx Hash receive :<br><a class="text-primary text-decoration-none" href="{{ $swap_transaction->tx_explorer_url_receive }}"><strong>{{ $swap_transaction->tx_hash_receive }}</strong></a></p>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
