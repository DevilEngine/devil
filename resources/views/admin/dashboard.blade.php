@extends('layout.admin')

@section('content')
    <h4 class="mb-0">Admin panel</h4>
    <hr>
    <div class="row row-cols-1 row-cols-md-3 g-4">

    {{-- Sites --}}
    <div class="col">
    <div class="card h-100 shadow-sm border-0 text-bg-primary">
        <div class="card-body text-center">
        <div class="mb-2 fs-4">💼</div>
        <h5 class="card-title">Sites</h5>
        <p class="display-6">{{ $totalSites }}</p>
        <a href="{{ route('admin.sites.index') }}" class="btn btn-light btn-sm">Manage Sites</a>
        </div>
    </div>
    </div>

    {{-- Catégories --}}
    <div class="col">
    <div class="card h-100 shadow-sm border-0 text-bg-secondary">
        <div class="card-body text-center">
        <div class="mb-2 fs-4">🗂️</div>
        <h5 class="card-title">Categories</h5>
        <p class="fs-4">
            {{ $totalCategories }} <small>parents</small><br>
            {{ $totalSubCategories }} <small>subcategories</small>
        </p>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light btn-sm">Manage Categories</a>
        </div>
    </div>
    </div>

    {{-- Avis en attente --}}
    <div class="col">
    <div class="card h-100 shadow-sm border-0 text-bg-warning">
        <div class="card-body text-center">
        <div class="mb-2 fs-4">📝</div>
        <h5 class="card-title">Pending Reviews</h5>
        <p class="display-6">{{ $pendingReviews }}</p>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-light btn-sm">Moderate</a>
        </div>
    </div>
    </div>

    {{-- Utilisateurs --}}
    <div class="col">
        <div class="card h-100 shadow-sm border-0 text-bg-dark">
            <div class="card-body text-center">
            <div class="mb-2 fs-4">👥</div>
            <h5 class="card-title">Users</h5>
            <p class="display-6">{{ $usersCount }}</p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">Manage Users</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 shadow-sm border-0 text-bg-info">
            <div class="card-body text-center">
            <div class="mb-2 fs-4">🖼️</div>
            <h5 class="card-title">Active Banners</h5>
            <p class="display-6">{{ $bannersCount }}</p>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-light btn-sm">Manage Banners</a>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 shadow-sm border-0 text-bg-danger">
            <div class="card-body text-center">
            <div class="mb-2 fs-4">🌟</div>
            <h5 class="card-title">Featured Sites</h5>
            <p class="display-6">{{ $featuredSitesCount }}</p>
            <a href="{{ route('admin.sites.featured') }}" class="btn btn-light btn-sm">View Featured</a>
            </div>
        </div>
    </div>

    {{-- Avis approuvés --}}
    <div class="col">
    <div class="card h-100 shadow-sm border-0 text-bg-success">
        <div class="card-body text-center">
        <div class="mb-2 fs-4">✅</div>
        <h5 class="card-title">Approved Reviews</h5>
        <p class="display-6">{{ $approvedReviews }}</p>
        </div>
    </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger h-100">
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <h3>{{ $pendingReports }}</h3>
                <p class="mb-0">🚩 Unresolved Reports</p>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-light btn-sm mt-2">View reports</a>
            </div>
        </div>
    </div>

    </div>
    <hr class="my-5">
    <h3 class="mb-3">📈 Statistiques mensuelles</h3>
    <canvas id="statsChart" height="100"></canvas>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('statsChart').getContext('2d');

        new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($months->pluck('label')),
            datasets: [
            {
                label: 'Sites créés',
                data: @json($months->pluck('sites')),
                backgroundColor: 'rgba(13, 110, 253, 0.6)'
            },
            {
                label: 'Avis reçus',
                data: @json($months->pluck('reviews')),
                backgroundColor: 'rgba(25, 135, 84, 0.6)'
            }
            ]
        },
        options: {
            responsive: true,
            scales: {
            y: {
                beginAtZero: true,
                ticks: {
                precision: 0
                }
            }
            }
        }
        });
    });
    </script>

@endsection
