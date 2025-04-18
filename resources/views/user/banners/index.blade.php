@extends('layout.app')

@section('title', '📢 My Banners')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-4">📢 My Banners</h4>
                <hr>
                <a href="{{ route('banner-request.create') }}" class="btn btn-sm btn-outline-success mb-4">➕ Banner request</a>
                @if($banners->isEmpty())
                    <p class="text-muted">You haven’t used any banners yet.</p>
                @else
                    <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>Preview</th>
                            <th>Linked Site</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th>Link</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banners as $banner)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" style="max-width: 200px;">
                                </td>
                                <td>
                                    @if($banner->site)
                                    <a href="{{ route('site.show', $banner->site->slug) }}">{{ $banner->site->name }}</a>
                                    @else
                                    <em class="text-muted">[deleted]</em>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->expires_at && $banner->expires_at->isPast())
                                    <span class="badge bg-secondary">Expired</span>
                                    @elseif($banner->active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-dark">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $banner->expires_at ? $banner->expires_at->diffForHumans() : '∞' }}
                                </td>
                                <td>
                                    <a href="{{ $banner->url }}" class="btn btn-sm btn-outline-primary" target="_blank">🔗 Visit</a>

                                    <form action="{{ route('banners.destroy', $banner) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">🗑 Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
