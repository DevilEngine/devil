@extends('layout.admin')

@section('title', '⚙️ DevilCoin Packages')

@section('content')
<div class="container py-4">
  <h1 class="mb-4">⚙️ DevilCoin Packages</h1>

  <a href="{{ route('devilcoin-packages.create') }}" class="btn btn-success mb-3">
    ➕ Add New Package
  </a>

  @if($packs->isEmpty())
    <p class="text-muted">No packages yet.</p>
  @else
    <table class="table table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Amount (DEVC)</th>
          <th>Price (USD)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($packs as $package)
        <tr>
          <td>{{ $package->id }}</td>
          <td>{{ $package->amount }}</td>
          <td>{{ $package->usd_price }} USD</td>
          <td>
            @if($package->active)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-secondary">Disabled</span>
            @endif
          </td>
          <td>
            <a href="{{ route('devilcoin-packages.edit', $package) }}" class="btn btn-sm btn-outline-primary">✏️ Edit</a>
            <form action="{{ route('devilcoin-packages.destroy', $package) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this package?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">🗑 Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
