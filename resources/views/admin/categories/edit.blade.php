@extends('layout.admin')

@section('title', 'Edit category')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-0">Update category : {{ $category->name }}</h4>
        <hr>
        @if($errors->any())
            <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Emoji (optional)</label>
                <input type="text" name="emoji" class="form-control" value="{{ old('emoji', $category->emoji ?? '') }}" placeholder="Ex: 🎰">
                <div class="form-text">Use a valid emoji. Ex: 🎲, 💱, 🔐, 👛</div>
            </div>

            <div class="col-md-6">
            <label class="form-label">Catégorie parent (optionnel)</label>
            <select name="parent_id" class="form-select">
                <option value="">Aucune</option>
                @foreach($parents as $parent)
                <option value="{{ $parent->id }}" @selected($category->parent_id == $parent->id)>
                    {{ $parent->name }}
                </option>
                @endforeach
            </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description courte</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $category->description ?? '') }}</textarea>
                <small class="form-text text-muted">Cette description apparaîtra en haut de la page et dans les moteurs de recherche.</small>
            </div>

            <div class="col-12">
            <button type="submit" class="btn btn-success">Update</button>
            </div>
        </form>
    </div>
@endsection
