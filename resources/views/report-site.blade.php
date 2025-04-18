@extends('layout.app')

@section('title', 'Report website')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">🚩 Report <strong>{{ $site->name }}</strong></h4>
        <hr>
        <form method="POST" action="{{ route('site.report.submit', $site->slug) }}">
            @csrf

            <div class="mb-3">
            <label class="form-label">Reason for report</label>
            <select name="reason" class="form-select" required>
                <option value="">Select a reason</option>
                <option value="scam" {{ old('reason') == 'scam' ? 'selected' : '' }}>Scam or fraud</option>
                <option value="broken" {{ old('reason') == 'broken' ? 'selected' : '' }}>Broken link</option>
                <option value="inappropriate" {{ old('reason') == 'inappropriate' ? 'selected' : '' }}>Inappropriate content</option>
                <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            </div>

            <div class="mb-3">
            <label class="form-label">Additional info (optional)</label>
            <textarea name="message" class="form-control" rows="4">{{ old('message') }}</textarea>
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
@endsection
