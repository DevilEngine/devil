@extends('layout.admin')

@section('content')
    <h4 class="mb-0">Edit banner</h4>
    <hr>
    <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Titre (facultatif)</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}">
        </div>

        <div class="mb-3">
            <label>Image actuelle :</label><br>
            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Bannière actuelle" class="img-fluid mb-2" style="max-height: 120px;">
        </div>

        <div class="mb-3">
            <label>Remplacer l’image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <small class="form-text text-muted">
            Format recommandé : 1200×300 (ratio 4:1). JPG, PNG, WEBP. Taille max : 2 Mo.
        </small>

        <div class="mb-3">
            <label>URL</label>
            <input type="url" name="url" class="form-control" value="{{ old('url', $banner->url) }}" required>
        </div>

        <div class="mb-3">
            <label>Position (1 to 6)</label>
            <input type="number" name="position" class="form-control" min="1" max="6" value="{{ old('position', $banner->position) }}" required>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" id="active"
            {{ old('active', $banner->active) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Activer la bannière</label>
        </div>

        <button class="btn btn-success">Update</button>
    </form>
@endsection
