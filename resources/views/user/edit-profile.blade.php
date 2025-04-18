@extends('layout.app')

@section('title', '✏️ Edit your profile')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h4 class="mb-4">✏️ Edit your profile</h4>
            <hr>

            <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="bio" class="form-label">🧾 Bio / Description</label>
                    <textarea name="bio" id="bio" rows="5" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <div class="mb-3 text-center">
                    <label class="form-label">🧑‍🎨 Current Avatar</label><br>
                    <img src="{{ auth()->user()->avatarUrl() }}"
                        alt="{{ auth()->user()->name }}"
                        class="rounded-circle shadow"
                        width="100" height="100">
                </div>

                <div class="mb-3">
                    <label class="form-label">📤 Upload Avatar</label>
                    <input type="file" name="avatar" class="form-control">
                    <div class="form-text">📐 JPG or PNG — square image recommended</div>
                </div>

                @if(auth()->user()->avatar_path)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="delete_avatar" id="delete_avatar" value="1">
                        <label class="form-check-label" for="delete_avatar">
                            🗑️ Remove current avatar and use default
                        </label>
                    </div>
                @endif

                <hr class="my-4">
                <h5>🔐 Change Password</h5>

                <div class="mb-3">
                    <label class="form-label">🔑 New Password</label>
                    <input type="password" name="password" class="form-control" autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <label class="form-label">🔁 Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                </div>

                <button class="btn btn-success">💾 Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
