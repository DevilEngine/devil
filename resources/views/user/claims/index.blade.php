@extends('layout.app')

@section('title', '🙋 My Site Claims')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>
        <div class="col-md-9">
            <h4 >🙋 My Site Claims</h4>
            <hr>
            @if($claims->isEmpty())
                <p class="text-muted">You haven’t submitted any claims yet.</p>
            @else
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Site</th>
                            <th>Proof</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $claim)
                            <tr>
                                <td>
                                    <a href="{{ route('site.show', $claim->site->slug) }}">
                                        {{ $claim->site->name }}
                                    </a>
                                </td>
                                <td>
                                    {{ ucfirst($claim->proof_type) }}<br>
                                    <small class="text-muted">{{ Str::limit($claim->proof_details, 60) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $claim->status === 'pending' ? 'warning text-dark' : ($claim->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ ucfirst($claim->status) }}
                                    </span>
                                    @if($claim->status === 'rejected' && $claim->admin_note)
                                        <br><small class="text-muted">{{ $claim->admin_note }}</small>
                                    @endif
                                </td>
                                <td>{{ $claim->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
