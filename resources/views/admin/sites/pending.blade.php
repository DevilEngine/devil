@extends('layout.admin')

@section('content')
    <div class="container">
        <h4 class="mb-4">Sites Pending Approval</h4>
        <hr>
        
        @if($sites->count())
            <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                <th>Name</th>
                <th>URL</th>
                <th>Category</th>
                <th>Submitted</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sites as $site)
                <tr>
                    <td>{{ $site->name }}</td>
                    <td><a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a></td>
                    <td>{{ $site->category->name ?? '—' }}</td>
                    <td>{{ $site->created_at->format('Y-m-d') }}</td>
                    <td>
                    <form action="{{ route('admin.sites.approve', $site) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('admin.sites.destroy', $site) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this site?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
        @else
            <div class="alert alert-info">No sites pending for now.</div>
        @endif
    </div>
@endsection
