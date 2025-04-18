@extends('layout.admin')

@section('content')
  <h4 class="mb-0">Modération des avis</h4>
  <hr>
  
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Site</th>
        <th>Auteur</th>
        <th>Note</th>
        <th>Commentaire</th>
        <th>Statut</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($reviews as $review)
        <tr>
          <td>{{ $review->site->name }}</td>
          <td>
            {{ $review->user->username}}
            @if($review->user->isTrustedUser())
              <span class="badge bg-success ms-1">🛡 Trusted</span>
            @endif
          </td>
          <td>{{ $review->rating }} ⭐</td>
          <td>{{ Str::limit($review->comment, 80) }}</td>
          <td>
            @if($review->approved)
              <span class="badge bg-success">Approuvé</span>
            @else
              <span class="badge bg-warning text-dark">En attente</span>
            @endif
          </td>
          <td class="text-nowrap">
            @if(!$review->approved)
              <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="d-inline">
                @csrf
                @method('PATCH')
                <button class="btn btn-sm btn-outline-success">✅</button>
              </form>
            @endif

            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="d-inline">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cet avis ?')">🗑️</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $reviews->links() }}
@endsection
