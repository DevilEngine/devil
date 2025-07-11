@extends('layout.app')

@section('title','📤 Submit a website')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>

        <div class="col-md-9">
            <h4 class="mb-0">📤 Submit a website</h4>
            <hr>

            <div class="card mb-2">
                <div class="card-body">
                    @if(!auth()->user()->hasExtendedSubmissionLimit())
                        <p class="text-muted small mb-0">
                            🚧 You can only have <strong>1 site pending</strong> at a time.  
                            <br>
                            <span class="text-warning">🔓 Unlock extended limit at 50 DevilCoins 👿 🎁</span>
                        </p>
                    @else
                        <p class="text-success small mb-0">
                            🧾 Extended limit unlocked: <strong>3 pending sites</strong> allowed
                        </p>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('site.submit') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">🏷️ Site Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">🔗 Main URL</label>
                    <input type="url" name="url" class="form-control" required value="{{ old('url') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">🧬 Mirror URL 1 (optional)</label>
                    <input type="url" name="mirror_1" class="form-control" value="{{ old('mirror_1') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">🧬 Mirror URL 2 (optional)</label>
                    <input type="url" name="mirror_2" class="form-control" value="{{ old('mirror_2') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">📝 Description (optional)</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">📁 Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Select a sub-category --</option>
                        @foreach($categories as $parent)
                            <optgroup label="📂 {{ $parent->name }}">
                                @foreach($parent->children as $child)
                                    <option value="{{ $child->id }}" @selected(old('category_id') == $child->id)>
                                        └ {{ $child->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">🏷️ Tags <small>(max. 6)</small></label>
                    <select name="tags[]" class="form-select" multiple required size="6">
                        @foreach($tags as $tag)
                            <option value="{{ $tag }}" @if(collect(old('tags'))->contains($tag)) selected @endif>
                                {{ ucfirst($tag) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple tags. Max: 6.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">🖼 Logo (required)</label>
                    <input type="file" name="logo" class="form-control">
                </div>

                <h5 class="mt-4 mb-2">🔐 Privacy & Crypto Options</h5>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="no_kyc" id="no_kyc" {{ old('no_kyc', $site->no_kyc ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="no_kyc">🛡 No KYC Required</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="has_onion" id="has_onion" {{ old('has_onion', $site->has_onion ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_onion">🧅 Has Onion Mirror (.onion)</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="decentralized" id="decentralized" {{ old('decentralized', $site->decentralized ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="decentralized">🛰 Decentralized</label>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="smart_contract" id="smart_contract" {{ old('smart_contract', $site->smart_contract ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="smart_contract">📜 Uses Smart Contract</label>
                </div>

                <p>Confirm form by clicking on the cut circle:</p>
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
