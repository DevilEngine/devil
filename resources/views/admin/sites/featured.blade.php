@extends('layout.admin')

@section('content')
  <h4 class="mb-4">Featured Sites</h4>
  <hr>
  @if($sites->count())
    <table class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>Name</th>
          <th>Category</th>
          <th>Homepage</th>
          <th>Category Page</th>
          <th>Tag Pages</th>
          <th>Edit</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sites as $site)
          <tr>
            <td>{{ $site->name }}</td>
            <td>{{ $site->category->name ?? '—' }}</td>

            {{-- Featured Home --}}
            <td>
              @if($site->featured_home)
                <form method="POST" action="{{ route('admin.sites.toggleFeatured', $site) }}" class="d-inline">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="featured_home">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                </form>
              @else
                —
              @endif
            </td>

            {{-- Featured Category --}}
            <td>
              @if($site->featured_category)
                <form method="POST" action="{{ route('admin.sites.toggleFeatured', $site) }}" class="d-inline">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="featured_category">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                </form>
              @else
                —
              @endif
            </td>

            {{-- Featured Tag --}}
            <td>
              @if($site->featured_tag)
                <form method="POST" action="{{ route('admin.sites.toggleFeatured', $site) }}" class="d-inline">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="featured_tag">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                </form>
              @else
                —
              @endif
            </td>

            {{-- Edit --}}
            <td>
              <a href="{{ route('admin.sites.edit', $site) }}" class="btn btn-sm btn-primary">Edit</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <div class="alert alert-warning">No featured sites at the moment.</div>
  @endif
@endsection
