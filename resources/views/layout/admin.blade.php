<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">

  <link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/img/favicon.svg" />
  <link rel="shortcut icon" href="/img/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png" />
  <link rel="manifest" href="/img/site.webmanifest" />

  <title>Admin Panel – Devil Engine</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      min-height: 100vh;
    }
    .sidebar {
      min-width: 10%;
      min-height: 100vh;
      background-color: #343a40;
    }
    .sidebar a {
      color: #ffffff;
      text-decoration: none;
      display: block;
      padding: 10px 15px;
    }
    .sidebar a:hover {
      background-color: #495057;
    }
    .sidebar .active {
      background-color: #0d6efd;
    }
  </style>
</head>
<body>

  <div class="d-flex">
    <div class="sidebar">
        <div class="p-3 text-white fw-bold border-bottom">Admin Panel</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            📊Dashboard
        </a>

        <a href="{{ route('admin.sites.index') }}" class="{{ request()->is('admin/sites*') ? 'active' : '' }}">
            💼Sites
        </a>

        <a href="{{ route('admin.claims.index') }}" class="{{ request()->routeIs('admin.claims.index') ? 'active' : '' }}">
          📥 Site Claims
          @if($pendingClaimsCount > 0)
            <span class="badge bg-danger ms-1">{{ $pendingClaimsCount }}</span>
          @endif
        </a>

        <a href="{{ route('admin.sites.pending') }}" class="{{ request()->routeIs('admin.sites.pending') ? 'active' : '' }}">
          ⏳Pending Sites
          @if($pendingSitesCount > 0)
            <span class="badge bg-warning text-dark ms-1">{{ $pendingSitesCount }}</span>
          @endif
        </a>

        <a href="{{ route('admin.sites.featured') }}" class="{{ request()->routeIs('admin.sites.featured') ? 'active' : '' }}">
          🌟Featured Sites
        </a>

        <a href="{{ route('admin.reports.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
          🚩 Reports
          @if($pendingReportsCount > 0)
            <span class="badge bg-info text-dark ms-1">{{ $pendingReportsCount }}</span>
          @endif
        </a>

        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
          👥Users
        </a>

        <a href="{{ route('admin.categories.index') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
            🗂️Categories
        </a>

        <a href="{{ route('admin.banners.index') }}" class="{{ request()->is('admin/banners*') ? 'active' : '' }}">
          📢Banners
        </a>

        <a href="{{ route('banner-requests.index') }}"
          class="nav-link {{ request()->routeIs('banner-requests.*') ? 'active' : '' }}">
          📢 Banner Requests
        </a>

        <a href="{{ route('devilcoin-packages.index') }}"
          class="nav-link {{ request()->routeIs('devilcoin-packages.*') ? 'active' : '' }}">
          💰 DevilCoin Packages
        </a>

        <a href="{{ route('admin.reviews.index') }}" class="{{ request()->is('admin/avis*') ? 'active' : '' }}">
            📝Rating
        </a>

        <hr class="border-white">

        <a href="{{ route('home') }}">🏠Retour</a>

        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            🚪Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    <div class="flex-fill p-4">
    @if ($errors->any())
        <div class="container">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-success mt-2">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger mt-2">
                {{ session()->get('error') }}
            </div>
        @endif
    </div>

      @yield('content')
    </div>
  </div>

</body>
</html>
