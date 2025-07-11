@extends('layout.app')

@section('title', '📜 Withdrawal History')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>
        <div class="col-md-9">
            <h4 class="mb-0">📜 Withdrawal History</h4>
            <hr>
            <a class="btn btn-outline-success mb-4" href="{{ route('user.withdrawals.create') }}">💰 Withdrawal Request</a>
            @if($withdrawals->isEmpty())
                <p class="text-muted">You haven’t made any withdrawal requests yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $w)
                                <tr>
                                    <td>{{ $w->created_at->format('Y-m-d') }}</td>
                                    <td>👿 {{ $w->amount }} | {{ number_format($w->amount * $xmrRatePerDevilcoin, 6) }} XMR</td>
                                    <td><code>{{ $w->admin_note ?? '-' }}</code></td>
                                    <td>
                                        @if($w->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($w->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <p class="text-muted">
                📈 Exchange rate: 100 DevilCoins = <strong>0.01 XMR</strong><br>
                👉 Example: 1000 DevilCoins ≈ <strong>0.1 XMR</strong>
            </p>
        </div>
    </div>
</div>
@endsection
