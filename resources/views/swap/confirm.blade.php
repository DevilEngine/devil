{{-- resources/views/swap.blade.php --}}
@extends('layout.app')

@section('title', 'Confirming 🚀')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm bg-custom">
                <div class="card-header text-center bg-success text-white">
                    <h4 class="mb-0">Confirming 🚀</h4>
                </div>
                <div class="card-body">
                    {{-- Source --}}
                    <div class="row">
                        <div class="col-lg-5 text-center">
                            <p class="mb-0">You send</p>
                            <h3 class="fw-bolder">{{ session()->get('amount') }} {{ strtoupper(session()->get('fromCurrency')) }}</h3>
                        </div>
                        <div class="col-lg-2 text-center">
                            <img class="align-middle mt-2" src="{{asset('img/icon/arrow-right-solid.png')}}" width="40" />
                        </div>
                        <div class="col-lg-5 text-center">
                            <p class="mb-0">You get</p>
                            <h3 class="fw-bolder"> ≈{{ session()->get('estimate') }} {{ strtoupper(session()->get('toCurrency')) }}</h3>
                        </div>
                    </div>
                    <form action="{{ route('swap.create') }}" method="POST">
                        @csrf
                        <div class="row mt-4">
                            <div class="col-lg-9">
                                <input type="text" value="{{ old('address') }}" name="address" class="form-control mb-2 @error('address') is-invalid @enderror" placeholder="Recipient Address {{strtoupper(session()->get('toCurrency'))}}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <input type="text" value="{{ old('refundAddress') }}" name="refundAddress" class="form-control @error('refundAddress') is-invalid @enderror" placeholder="Refund Address {{strtoupper(session()->get('fromCurrency'))}}">
                                @error('refundAddress')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3">
                                <div class="align-middle">
                                    <button type="submit" class="btn btn-success w-100"><img src="{{ asset('img/icon/arrow-right-arrow-left-solid.png') }}" width="18" /> Confirm</button>
                                    <a href="{{ route('swap.start') }}" class="btn btn-secondary w-100 mt-2"><img src="{{ asset('img/icon/arrow-left-solid.png') }}" width="18" /> Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
