@extends('layout.admin')

@section('content')
    <div class="container">
        <h4 class="mb-4">🚩 Site Reports</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Site</th>
                <th>User</th>
                <th>Reason</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($reports as $report)
                <tr>
                <td><a href="{{ route('site.show', $report->site->slug) }}" target="_blank">{{ $report->site->name }}</a></td>
                <td>{{ $report->user?->name ?? 'Guest' }}</td>
                <td><span class="badge bg-warning text-dark">{{ ucfirst($report->reason) }}</span></td>
                <td>{{ $report->message ?? '-' }}</td>
                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if($report->resolved)
                    <span class="badge bg-success">Resolved</span>
                    @else
                    <span class="badge bg-danger">Pending</span>
                    @endif
                </td>
                <td>
                    @if(!$report->resolved)
                    <form method="POST" action="{{ route('admin.reports.resolve', $report) }}">
                        @csrf
                        <button class="btn btn-sm btn-success">✔ Mark as resolved</button>
                    </form>
                    @endif
                </td>
                </tr>
            @empty
                <tr>
                <td colspan="7" class="text-center text-muted">No reports yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
