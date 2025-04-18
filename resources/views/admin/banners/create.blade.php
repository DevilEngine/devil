@extends('layout.admin')

@section('content')
    <h4 class="mb-0">Create banner</h4>
    <hr>
    <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Titre (facultatif)</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label>Image (PNG/JPG)</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <small class="form-text text-muted">
            Format recommandé : 1200×300 (ratio 4:1). JPG, PNG, WEBP. Taille max : 2 Mo.
        </small>

        <div class="mb-3">
            <label>URL</label>
            <input type="url" name="url" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Position (1 to 6)</label>
            <input type="number" name="position" class="form-control" min="1" max="6" required>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" checked>
            <label class="form-check-label">Active banner</label>
        </div>

        <button class="btn btn-success">Save</button>
    </form>

@endsection
