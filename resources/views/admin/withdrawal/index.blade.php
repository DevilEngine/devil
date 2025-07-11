@extends('layout.admin')

@section('title', '💸 DevilCoin Withdrawals')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">💸 DevilCoin Withdrawals</h1>

        @if($withdrawals->isEmpty())
            <div class="alert alert-info">No withdrawal requests at this time.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Amount</th>
                            <th>XMR Address</th>
                            <th>Status</th>
                            <th>TX ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $w)
                            <tr>
                                <td>
                                    <a href="{{ route('user.public', $w->user->slug) }}">
                                        {{ $w->user->username }}
                                    </a>
                                </td>
                                <td>👿 {{ $w->amount }}</td>
                                <td>
                                    <code>{{ $w->xmr_address }}</code>
                                </td>
                                <td>
                                    @if($w->status === 'pending')
                                        <span class="badge bg-warning text-dark">⏳ Pending</span>
                                    @elseif($w->status === 'approved')
                                        <span class="badge bg-success">✅ Approved</span>
                                    @elseif($w->status === 'rejected')
                                        <span class="badge bg-danger">❌ Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($w->admin_note)
                                        <small class="text-muted">{{ $w->admin_note }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($w->status === 'pending')
                                        {{-- Approve --}}
                                        <form method="POST" action="{{ route('admin.withdrawal.approve', $w->id) }}" class="mb-2">
                                            @csrf
                                            <input type="text" name="tx_id" class="form-control form-control-sm mb-1"
                                                   placeholder="TX ID" required>
                                            <button class="btn btn-success btn-sm w-100">✅ Approve</button>
                                        </form>

                                        {{-- Reject --}}
                                        <form method="POST" action="{{ route('admin.withdrawal.reject', $w->id) }}"
                                              onsubmit="return confirm('Reject this withdrawal request?');">
                                            @csrf
                                            <button class="btn btn-outline-danger btn-sm w-100">❌ Reject</button>
                                        </form>
                                    @else
                                        <em class="text-muted">Processed</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
