@extends('layout.admin')

@section('content')
    <h4 class="mb-0">Bannières promotionnelles</h4>
        <hr>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">Add banner</a>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Position</th>
            <th>Titre</th>
            <th>Image</th>
            <th>URL</th>
            <th>Active</th>
            <th>Actions</th>
            <th>Expire</th>
            <th>Started</th>
        </tr>
        </thead>
        <tbody>
        @foreach($banners as $banner)
            <tr>
            <td>{{ $banner->position }}</td>
            <td>{{ $banner->title ?? '—' }}</td>
            <td><img src="{{ asset('storage/' . $banner->image_path) }}" width="100"></td>
            <td>
            @if($banner->external_url)
                <p><strong>External URL:</strong> <a href="{{ $banner->external_url }}" target="_blank">{{ $banner->external_url }}</a></p>
            @elseif($banner->site)
                <p><strong>Linked Site:</strong> <a href="{{ route('site.show', $banner->site) }}" target="_blank">{{ $banner->site->name }}</a></p>
            @else
                <p class="text-danger">No URL linked</p>
            @endif
            </td>
            <td>{{ $banner->active ? '✅' : '❌' }}</td>
            <td>
                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning">✏️</a>
                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">🗑️</button>
                </form>
            </td>
            <td>
                @if($banner->expires_at)
                    <small class="text-muted">{{ $banner->expires_at->diffForHumans() }}</small>
                @else
                    <small class="text-muted">∞</small>
                @endif
            </td>
            <td>
                {{ $banner->created_at->format('d m y') }} – <i>{{ $banner->created_at->diffForHumans() }}</i>
            </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
