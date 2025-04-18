@extends('layout.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">📢 Banner Promotion Requests</h1>

    @if($requests->isEmpty())
        <p class="text-muted">No pending banner requests.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Preview</th>
                        <th>Target</th>
                        <th>Submitted By</th>
                        <th>Slot</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/' . $req->image) }}" alt="Banner" style="max-width: 200px;">
                        </td>
                        <td>
                            @if($req->external_url)
                                <span class="badge bg-secondary mb-1">🌐 External URL</span><br>
                                <a href="{{ $req->external_url }}" target="_blank">
                                    {{ \Illuminate\Support\Str::limit($req->external_url, 40) }}
                                </a>
                            @elseif($req->site)
                                <span class="badge bg-primary mb-1">💠 Linked Site</span><br>
                                <a href="{{ route('site.show', $req->site->slug) }}" target="_blank">
                                    {{ $req->site->name }}
                                </a>
                            @else
                                <span class="text-muted">No URL</span>
                            @endif
                        </td>
                        <td>
                            {{ $req->user->username }}<br>
                            <small class="text-muted">{{ $req->created_at->diffForHumans() }}</small>
                        </td>
                        <td class="text-center">Slot {{ $req->position }}</td>
                        <td>
                            @if($req->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                                <br><small class="text-muted">{{ $req->admin_note }}</small>
                            @endif
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <form action="{{ route('banner-requests.approve', $req->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success mb-1">✅ Approve</button>
                                </form>

                                <form action="{{ route('banner-requests.reject', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this request?');">
                                    @csrf
                                    <input type="hidden" name="admin_note" value="Rejected by admin.">
                                    <button class="btn btn-sm btn-outline-danger">❌ Reject</button>
                                </form>
                            @else
                                <em class="text-muted">No action</em>
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
