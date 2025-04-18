@extends('layout.admin')

@section('content')
    <h4 class="mb-0">User Management</h4>
    <hr>
    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th>Name</th>
            <th>Admin</th>
            <th>Banned</th>
            <th>Joined</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
            <td>{{ $user->username }}</td>
            <td>
                <span class="badge {{ $user->is_admin ? 'bg-success' : 'bg-secondary' }}">
                {{ $user->is_admin ? 'Yes' : 'No' }}
                </span>
            </td>
            <td>
                <span class="badge {{ $user->banned ? 'bg-danger' : 'bg-success' }}">
                {{ $user->banned ? 'Banned' : 'Active' }}
                </span>
            </td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
            <td>
                <form action="{{ route('admin.users.toggleAdmin', $user) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-warning">
                        {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                    </button>
                </form>

                <form action="{{ route('admin.users.toggleBan', $user) }}" method="POST" class="d-inline ms-2">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm {{ $user->banned ? 'btn-success' : 'btn-danger' }}">
                        {{ $user->banned ? 'Unban' : 'Ban' }}
                    </button>
                </form>
            </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
