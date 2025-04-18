@extends('layout.admin')

@section('title', '👁️ Review Claim')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">👁️ Claim Review for: {{ $claim->site->name }}</h3>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Claimed by:</strong> {{ $claim->user->username }}</li>
        <li class="list-group-item"><strong>Proof Type:</strong> {{ ucfirst($claim->proof_type) }}</li>
        <li class="list-group-item"><strong>Details:</strong> {{ $claim->proof_details }}</li>
        @if($claim->message)
            <li class="list-group-item"><strong>Message:</strong> {{ $claim->message }}</li>
        @endif
        <li class="list-group-item"><strong>Status:</strong> 
            <span class="badge bg-{{ $claim->status === 'pending' ? 'warning text-dark' : ($claim->status === 'approved' ? 'success' : 'danger') }}">
                {{ ucfirst($claim->status) }}
            </span>
        </li>
    </ul>

    @if($claim->status === 'pending')
        <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" class="d-inline">
            @csrf
            <button class="btn btn-success">✅ Approve & transfer ownership</button>
        </form>

        <form method="POST" action="{{ route('admin.claims.reject', $claim) }}" class="d-inline ms-2">
            @csrf
            <input type="text" name="admin_note" placeholder="Rejection note (optional)" class="form-control d-inline w-auto" />
            <button class="btn btn-outline-danger">❌ Reject</button>
        </form>
    @endif
</div>
@endsection
