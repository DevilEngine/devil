@extends('layout.admin')

@section('title', 'All websites')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Websites</h4>
            <a href="{{ route('admin.sites.create') }}" class="btn btn-success">Add website</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>URL</th>
                <th>Category</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sites as $site)
                <tr>
                    <td>
                    <img src="{{ $site->logoOrAvatarUrl() }}" width="60" alt="{{ $site->name }}">
                    </td>
                    <td>{{ $site->name }}</td>
                    <td><a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a></td>
                    <td>{{ $site->category->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($site->type) }}</td>
                    <td>
                        @if($site->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($site->status === 'inactive')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($site->status === 'scam')
                            <span class="badge bg-danger">Scam</span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                    <a href="{{ route('admin.sites.edit', $site) }}" class="btn btn-sm btn-outline-warning">✏️</a>
                    <form action="{{ route('admin.sites.destroy', $site) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">🗑️</button>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>

        {{ $sites->links() }}
    </div>
@endsection
