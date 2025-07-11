{{-- resources/views/swap.blade.php --}}
@extends('layout.app')

@section('title', 'Start 🚀')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm bg-custom">
                <div class="card-header text-center bg-success text-white">
                    <h4 class="mb-0">👿 Devil Swap 🚀</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('swap.estimate') }}" method="POST">
                    @csrf
                        {{-- Source --}}
                        <div class="mb-3">
                            <div class="input-group">
                                <select class="form-control @error('from_currency') is-invalid @enderror" id="from_currency" name="fromCurrency">
                                    @foreach($currencies as $item)
                                        @if($item->legacy_symbol != "xmr")
                                            <option value="{{ $item->legacy_symbol }}" @if(session()->get('startfromCurrency') == $item->legacy_symbol) selected @endif>{{ $item->name }} - {{ strtoupper($item->legacy_symbol) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('fromCurrency')
                                    <span class="invalid-feedback mb-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="text-center me-2 ms-2">
                                    <label for="switch">
                                        <button type="submit" id="switch" class="btn btn-success" name="action" value="switch"><img src="{{ asset('img/icon/rotate-solid.png') }}" width="30" /></button>
                                    </label>
                                </div>
                                <select class="form-control @error('toCurrency') is-invalid @enderror" id="to_currency" name="toCurrency">
                                    @foreach($currencies as $item)
                                        @if($item->legacy_symbol != "xmr")
                                            <option value="{{ $item->legacy_symbol }}" @if(session()->get('starttoCurrency') == $item->legacy_symbol) selected @endif>{{ $item->name }} - {{ strtoupper($item->legacy_symbol) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('toCurrency')
                                    <span class="invalid-feedback mb-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        {{-- Destination --}}
                        <div class="mb-3 mt-2">
                            <div class="input-group">
                                <input type="text" class="form-control" name="amount" placeholder="Amount you want to send">
                            </div>
                        </div>

                        {{-- Bouton d'action --}}
                        <!--div class="d-grid">
                            <button value="estimate" type="submit" name="action" class="btn btn-success">
                                <h4 class="mb-0">Swap</h4>
                            </button>
                        </div-->
                        <p>Confirm swap by clicking on the cut circle:</p>
                        <div class="captcha-container">
                            <input type="image"
                            src="{{ $captcha['image'] }}"
                            name="captcha"
                            alt="captcha"
                            title="Click on cut circle">
                        </div>
                    </form>
                </div>
            </div>


            <div class="card bg-custom mt-4">
                <div class="card-body">
                    <p class="mb-0">Do you want convert XMR to cryptocurrency ? <strong>Go on <a href="https://xmrglobal.io/swap/service">XMRGLOBAL</a></strong></p>
                </div>
            </div>

            <div class="row text-center mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-lg rounded-4 bg-custom">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="display-5">🔒</span>
                            </div>
                            <h5 class="card-title text-success fw-bold">No JavaScript Needed</h5>
                            <p class="card-text text-muted">Fully functional without JavaScript. Ideal for privacy-focused and minimal setups.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-lg rounded-4 bg-custom">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="display-5">🧅</span>
                            </div>
                            <h5 class="card-title text-success fw-bold">Tor Compatible</h5>
                            <p class="card-text text-muted">Access and use the service seamlessly over the Tor network—no tracking, no restrictions.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-lg rounded-4 bg-custom">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="display-5">🕵️</span>
                            </div>
                            <h5 class="card-title text-success fw-bold">Full Anonymity</h5>
                            <p class="card-text text-muted">No sign-up, no personal data. Your swaps remain private and untraceable.</p>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card mt-4 mb-4 hadow-sm bg-custom">
                <div class="card-header text-center bg-success text-white"><h3 class="text-center mb-0">View progress of my swap 🔍</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('swap.search') }}">
                        @csrf
                        <input type="text" value="{{ old('identifier') }}" name="identifier" class=" mb-4 form-control @error('identifier') is-invalid @enderror" id="floatingInputGroup1" placeholder="Swap ID">
                        <p>Confirm search by clicking on the cut circle:</p>
                        <div class="captcha-container">
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
    </div>
</div>
@endsection
