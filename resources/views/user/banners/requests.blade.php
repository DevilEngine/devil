@extends('layout.app')

@section('title', '📋 My Banner Requests')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            @include('user.partials.sidebar')
        </div>
        <div class="col-md-9">
            <h4 class="mb-0">📋 My Banner Requests</h4>
            <hr>

            @if($requests->isEmpty())
                <p class="text-muted">You haven’t submitted any banner promotion requests yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark">
                        <tr>
                            <th>Banner</th>
                            <th>Target</th>
                            <th>Slot</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $req)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $req->image) }}" alt="Banner" style="max-width: 200px;">
                                </td>
                                <td>
                                    @if($req->site)
                                        <a href="{{ route('site.show', $req->site->slug) }}" target="_blank">
                                            {{ $req->site->name }}
                                        </a>
                                    @elseif($req->external_url)
                                        <a href="{{ $req->external_url }}" target="_blank">
                                            🌐 {{ \Illuminate\Support\Str::limit($req->external_url, 40) }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    Slot {{ $req->position }}
                                </td>
                                <td>
                                    @if($req->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($req->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                        @if($req->admin_note)
                                            <br><small class="text-muted">{{ $req->admin_note }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{ $req->created_at->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
