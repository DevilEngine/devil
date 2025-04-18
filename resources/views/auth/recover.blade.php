@extends('layout.app')

@section('title', 'Recover account')

@section('content')
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header fs-5">Recover account</div>
                        <div class="card-body">
                        
                        <form id="captcha-form" action="#" method="POST">
                            @csrf

                            <input type="text" name="check_user" style="display:none;">

                            <div class="form-floating mb-4">
                                <input type="text" name="username" class="form-control" id="floatingInput1" placeholder="username">
                                <label for="floatingInput1">Username <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating mb-4">
                                <textarea name="mnemonic" class="form-control" id="floatingInput1" placeholder="mnemonic"></textarea>
                                <label for="floatingInput1">Mnemonic key <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating">
                                <input type="password" name="new_password" class="form-control" id="floatingInput1" placeholder="new_password">
                                <label for="floatingInput1">Password <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" name="new_password_confirmation" class="form-control" id="floatingInput1" placeholder="password_confirmation">
                                <label for="floatingInput1">Confirm Password <span class="text-danger">*</span></label>
                            </div>
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

