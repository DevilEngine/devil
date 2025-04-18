@extends('layout.admin')

@section('title', '🏁 Ownership Claims')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🏁 Pending Site Ownership Claims</h2>
    @if($claims->isEmpty())
        <p class="text-muted">No claims submitted yet.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Site</th>
                    <th>Requested by</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($claims as $claim)
                    <tr>
                        <td>{{ $claim->site->name }}</td>
                        <td>{{ $claim->user->username }}</td>
                        <td>{{ ucfirst($claim->proof_type) }}</td>
                        <td><span class="badge bg-{{ $claim->status === 'pending' ? 'warning text-dark' : ($claim->status === 'approved' ? 'success' : 'danger') }}">{{ ucfirst($claim->status) }}</span></td>
                        <td>{{ $claim->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.claims.show', $claim) }}" class="btn btn-sm btn-outline-primary">👁️ View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $claims->links() }}
    @endif
</div>
@endsection
