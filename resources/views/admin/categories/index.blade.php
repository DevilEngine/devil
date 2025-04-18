@extends('layout.admin')

@section('title', 'Categories')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Categories</h4>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Add new category</a>
        </div>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Emoji</th>
                    <th>Parent</th>
                    <th>Number sub-category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td style="font-size: 1.5rem;">{{ $category->emoji ?? '📂' }}</td>
                    <td>—</td>
                    <td>{{ $cat->children->count() }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning">✏️</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove ?')">🗑️</button>
                        </form>
                    </td>
                </tr>

                {{-- Afficher les sous-catégories --}}
                @foreach($cat->children as $sub)
                <tr class="table-dark">
                    <td>↳ {{ $sub->name }}</td>
                    <td style="font-size: 1.5rem;">{{ $sub->emoji ?? '📂' }}</td>
                    <td>{{ $cat->name }}</td>
                    <td>—</td>
                    <td class="text-nowrap">
                    <a href="{{ route('admin.categories.edit', $sub) }}" class="btn btn-sm btn-outline-warning">✏️</a>
                    <form action="{{ route('admin.categories.destroy', $sub) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove ?')">🗑️</button>
                    </form>
                    </td>
                </tr>
                @endforeach

            @endforeach
            </tbody>
        </table>
    </div>
@endsection
