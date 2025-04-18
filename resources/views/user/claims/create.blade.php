@extends('layout.app')

@section('title', '🛡️ Claim Site Ownership')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4>🛡️ Claim Ownership of "{{ $site->name }}"</h4>
                <hr>
                <div class="alert alert-info">
                    Please provide details to prove that you are the legitimate owner of this website.
                </div>
                <form method="POST" action="{{ route('site.claim.submit', $site->slug) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Choose verification method</label>
                        <select name="proof_type" class="form-select" required>
                            <option value="">-- Select method --</option>
                            <option value="meta">Add verification meta tag</option>
                            <option value="file">Upload a verification file</option>
                            <option value="email">Send email from domain</option>
                            <option value="other">Other</option>
                        </select>
                        @error('proof_type') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <hr>
                    <h5>🧾 Verification Instructions</h5>

                    <div class="mb-3">
                        <h6>📌 1. Add a meta tag to your site</h6>
                        <p>Insert this into the <code>&lt;head&gt;</code> section of your homepage:</p>
<pre class="bg-dark p-2 rounded small border">&lt;meta name="site-verification" content="user-{{ auth()->user()->username }}-site-{{ $site->name }}"&gt;</pre>
                        <p class="text-muted small">We'll check your site manually to confirm this tag is present.</p>
                    </div>

                    <div class="mb-3">
                        <h6>📂 2. Upload a file to your root directory</h6>
                        <p>Create a text file named <strong>verification.txt</strong> and upload it to your root domain.</p>
                        <pre class="bg-dark p-2 rounded small border">Verify: user-{{ auth()->user()->username }}-site-{{ $site->name }}</pre>
                        <p class="text-muted small">We'll try accessing <code>yourdomain.com/verification.txt</code></p>
                    </div>

                    <div class="mb-3">
                        <h6>📧 3. Email from domain</h6>
                        <p>Send an email from a domain-linked address (e.g., <code>you@yourdomain.com</code>) to our admin address.</p>
                        <p><strong>Subject:</strong> Site Ownership Verification - {{ $site->name }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>🛠️ 4. Other method</h6>
                        <p>Explain any other way you can prove ownership (ex: contact via official channel, screenshot from admin panel, etc).</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Details / Instructions</label>
                        <textarea name="proof_details" class="form-control" rows="4" required placeholder="Provide instructions or URL to verify...">{{ old('proof_details') }}</textarea>
                        @error('proof_details') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Optional Message</label>
                        <textarea name="message" class="form-control" rows="3">{{ old('message') }}</textarea>
                    </div>

                    <p class="mb-2">Confirm form by clicking on the cut circle:</p>
                    <div class="captcha-container mb-4">
                        <input type="image"
                            src="{{ $captcha['image'] }}"
                            name="captcha"
                            alt="captcha"
                            title="Click on cut circle">
                    </div>
                    <p><a href="{{ route('site.show', $site->slug) }}" class="btn btn-outline-secondary">Cancel</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection
