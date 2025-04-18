@extends('layout.app')

@section('title', '🧾 Purchase History')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4>🧾 Purchase History</h4>
                <hr>
                @if($purchases->isEmpty())
                    <p class="text-muted">You haven't made any purchases yet.</p>
                @else
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>Date</th>
                        <th>Amount (DEVC)</th>
                        <th>Crypto</th>
                        <th>Status</th>
                        <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $p)
                            <tr>
                                <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $p->amount }}</td>
                                <td>{{ $p->pay_currency ?? '—' }}</td>
                                <td>
                                    @if($p->status === 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                    @elseif($p->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                    <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->invoice_url)
                                    <a href="{{ $p->invoice_url }}" target="_blank" class="btn btn-sm btn-outline-success">Pay</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
