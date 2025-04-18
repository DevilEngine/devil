@extends('layout.app')

@section('title', 'Register')

@section('content')
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header fs-5">Register</div>
                        <div class="card-body">
                        
                        <form id="captcha-form" action="{{ route('register.action') }}" method="POST">
                            @csrf

                            <input type="text" name="check_user" style="display:none;">

                            <div class="form-floating mt-2">
                                <input type="text" name="username" value="{{ old('username') }}" class="form-control" id="floatingInput" placeholder="username">
                                <label for="floatingInput">Username <span class="text-danger">*</span></label>
                            </div>
                            <br>

                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="floatingInput1" placeholder="password">
                                <label for="floatingInput1">Password <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating">
                                <input type="password" name="password_confirmation" class="form-control" id="floatingInput1" placeholder="password_confirmation">
                                <label for="floatingInput1">Confirm Password <span class="text-danger">*</span></label>
                            </div>
                            <br>
                            <p>Confirm form by clicking on the cut circle:</p>
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

