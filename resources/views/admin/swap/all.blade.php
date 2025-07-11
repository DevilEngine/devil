@extends('layout.admin')

@section('content')
    <h4 class="mb-0">Swaps All</h4>
    <hr>
    <table class="table table-bordered table-striped align-middle">
        <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>&nbsp;</th>
            <th>From→To</th>
            <th>Amount send</th>
            <th>Amount receive</th>
            <th>Tombola</th>
            <th>Status</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
            @foreach($swaps as $swap)
                <tr>
                    <td class="align-middle">{{ $swap->identifier }}</td>
                    <td class="align-middle">{{ $swap->created_at->format('d/m/y') }}</td>
                    <td>
                        @if($swap->token == "Test swap")
                            <span class="badge bg-success">From test</span>
                        @else
                            <span class="badge bg-success">From web</span>
                        @endif
                    </td>
                    <td class="align-middle">{{ strtoupper($swap->fromCurrency) }}→{{ strtoupper($swap->toCurrency) }}</td>
                    <td class="align-middle">{{ $swap->amount_to_send }} {{ strtoupper($swap->fromCurrency) }}</td>
                    <td class="align-middle">{{ $swap->amount_to_receive }} {{ strtoupper($swap->toCurrency) }}</td>
                    <td class="align-middle">
                        @if($swap->tombola_recipient)
                            @if($swap->status != "finished")
                                <span class="badge bg-primary">Pending</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        @else
                            <span class="badge bg-danger">Not active</span>
                        @endif
                    </td>
                    <td class="align-middle">
                        @if($swap->status == "waiting")
                            <span class="badge bg-danger">
                        @endif
                        @if($swap->status == "verifying" || $swap->status == "sending" || $swap->status == "exchanging" || $swap->status == "confirming" || $swap->status == "refunded")
                            <span class="badge bg-info">
                        @endif
                        @if($swap->status == "finished")
                            <span class="badge bg-success">
                        @endif
                        @if($swap->status == "expired" || $swap->status == "failed")
                            <span class="badge bg-info">
                        @endif
                            {{ ucfirst($swap->status) }}
                        </span>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="#"><img src="{{ asset('img/icon/eye-solid.png') }}" width="18" disabled /></a>
                    </td>
                </tr>
            @endforeach
            @if($swaps->isEmpty())
                <tr>
                    <td colspan="5"><div class="alert alert-warning text-center">No favorites</div></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div>{{ $swaps->links('pagination::simple-bootstrap-5') }}</div>
@endsection
