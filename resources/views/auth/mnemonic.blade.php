@extends('layout.app')

@section('content')
        <div class="container mt-4">
            <div class="card">
                <div class="card-header fs-5">Mnemonic</div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-body bg-dark">
                            <p class="select-all">{{ session()->get('mnemonic') }}</p>
                        </div>
                    </div>
                    <div class="alert alert-primary mt-4">Copy/paste this mnemonic key somewhere safe.</div>
                    <form method="POST" action="{{ route('register.mnemonic.confirm') }}">
                        @csrf
                        <button type="submit" class="btn btn-success">I have save this mnemonic key</button>
                    </form>
                </div>
            </div>
        </div>
@endsection

