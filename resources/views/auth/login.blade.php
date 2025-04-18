@extends('layout.app')

@section('title', 'Login')

@section('content')
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header fs-5">Login</div>
                        <div class="card-body">
                        
                        <form id="captcha-form" action="{{ route('login.action') }}" method="POST">
                            @csrf

                            <input type="text" name="check_user" style="display:none;">

                            <div class="form-floating">
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control" id="floatingInput" placeholder="username">
                                <label for="floatingInput">Username <span class="text-danger">*</span></label>
                            </div>
                            <br>

                            <div class="form-floating mb-4">
                                <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="password">
                                <label for="floatingInput1">Password <span class="text-danger">*</span></label>
                            </div>

                            <div class="row">
                                <div class="col-lg-7">
                                    <p>Confirm form by clicking on the cut circle:</p>
                                    <div class="captcha-container">
                                        <input type="image"
                                        src="{{ $captcha['image'] }}"
                                        name="captcha"
                                        alt="captcha"
                                        title="Click on cut circle">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <p class="mb-0 text-center">Do you have forget your password ?</p>
                                    <a href="{{ route('recover') }}" class="btn btn-success mt-2 w-100"><img src="{{ asset('img/icon/key-solid.png') }}" width="18" /> Account Recovery</a>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

