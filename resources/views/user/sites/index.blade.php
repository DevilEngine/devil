@extends('layout.app')

@section('title', '📂 My Sites')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                @include('user.partials.sidebar')
            </div>
            <div class="col-md-9">
                <h4 class="mb-4">📂 My Sites</h4>
                <hr>
                @if($sites->count())
                    <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sites as $site)
                        <tr>
                            <td>
                                <strong>{{ $site->name }}</strong>
                                @if($site->isCurrentlyFeatured())
                                    <div class="mt-1">
                                        <span class="badge bg-success">
                                            ⭐ Featured until 
                                            @php
                                                $untils = collect([
                                                    $site->featured_home_until,
                                                    $site->feature_category_until,
                                                    $site->feature_tag_until
                                                ])->filter(fn($d) => $d !== null);

                                                $latest = $untils->sortDesc()->first();
                                            @endphp
                                            {{ $latest->format('Y-m-d') }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td>
                            <span class="badge bg-{{ $site->status === 'active' ? 'success' : ($site->status === 'inactive' ? 'secondary' : 'danger') }}">
                                {{ ucfirst($site->status) }}
                            </span>
                            </td>
                            <td>{{ $site->category->name ?? '-' }}</td>
                            <td>
                            <a href="{{ route('sites.edit', $site) }}" class="btn btn-sm btn-outline-success">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('sites.stats', $site) }}" class="btn btn-sm btn-outline-info">
                                📊 Stats
                            </a>
                            <a href="{{ route('sites.feature.page', $site) }}" class="btn btn-sm btn-outline-warning mb-1">
                                ⭐ Promote
                            </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                @else
                    <p class="text-muted">You haven't submitted any site yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
