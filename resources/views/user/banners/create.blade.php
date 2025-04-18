@extends('layout.app')

@section('title', '📢 Request Banner Promotion')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h4 class="mb-4">📢 Request Banner Promotion</h4>
            <hr>

            <div class="alert alert-info">
                😈 You currently have <strong>{{ auth()->user()->devilCoins() }}</strong>.
            </div>

            <form method="POST" action="{{ route('banner-request.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">🔗 Choose a site OR external URL</label>

                    <select name="site_id" class="form-select mb-2">
                        <option value="">🌐 -- Select a site (optional) --</option>
                        @foreach($sites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                        @endforeach
                    </select>

                    <input type="url" name="external_url" class="form-control" placeholder="🌍 Or type an external URL (https://...)" value="{{ old('external_url') }}">
                    @error('site_id') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">📝 Title (optional)</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                    @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">🎯 Banner Position</label>
                    <select name="position" class="form-select" required>
                        <option value="">🧩 -- Choose a position --</option>
                        @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}"
                                {{ old('position') == $i ? 'selected' : '' }}
                                {{ in_array($i, $usedPositions) ? 'disabled' : '' }}>
                                📍 Slot {{ $i }} {{ in_array($i, $usedPositions) ? '🚫 Taken' : '' }}
                            </option>
                        @endfor
                    </select>
                    <small class="text-muted">🎯 Only available slots are shown (max 6 banners active).</small>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">⏳ Duration & DevilCoin Cost</label>
                    <select name="duration" id="duration" class="form-select" required>
                        <option value="">🕒 -- Select duration --</option>
                        <option value="1_week" {{ old('duration') == '1_week' ? 'selected' : '' }}>
                            📅 1 Week (💰 1000 DEVC)
                        </option>
                        <option value="2_weeks" {{ old('duration') == '2_weeks' ? 'selected' : '' }}>
                            📅 2 Weeks (💰 1500 DEVC)
                        </option>
                        <option value="1_month" {{ old('duration') == '1_month' ? 'selected' : '' }}>
                            📅 1 Month (💰 2000 DEVC)
                        </option>
                    </select>
                    @error('duration') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">🖼 Upload banner image</label>
                    <input type="file" name="image" id="image" class="form-control" required accept="image/png, image/jpeg">
                    <div class="form-text">📐 Recommended size: 1200×300 (JPG or PNG)</div>
                    @error('image') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <p class="mb-2">Confirm form by clicking on the cut circle:</p>
                <div class="captcha-container mb-4">
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
@endsection
