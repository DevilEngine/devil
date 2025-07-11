<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="@yield('meta_description', 'Explore and rate privacy-focused websites on the darknet and clearnet. Discover trusted platforms, read user reviews, and track reputation with DevilCoins.')">
        <meta name="author" content="" />
        <title>@yield('title') – 👿 Devil Engine</title>
        <!-- Favicon-->
        <link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/img/favicon.svg" />
        <link rel="shortcut icon" href="/img/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png" />
        <link rel="manifest" href="/img/site.webmanifest" />

        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    </head>
    <body>
        <!-- Responsive navbar-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand instru-font mb-2" href="/">Devil Engine | 👿🤘💰</a>
                <div class="navbar-collapse">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 flex-row">
                        <li class="nav-item"><a class="btn btn-success me-2" href="{{ route('swap.start') }}">👿 Devil Swap 🚀</a></li>
                        @guest
                            <li class="nav-item"><a class="btn btn-outline-success me-2" href="{{ route('login') }}">💻 Login</a></li>
                            <li class="nav-item"><a class="btn btn-success" href="{{ route('register') }}">🫵 Register</a></li>
                        @endguest
                        @auth
                            <li class="nav-item">
                                <a href="{{ route('user.dashboard') }}" class="btn btn-success me-2">
                                    📂 Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard.rewards') }}" class="btn btn-outline-success me-2">
                                    😈 {{ auth()->user()->devilCoins() }}
                                </a>
                            </li>
                            @if(Auth::user()->is_admin)
                                <li class="nav-item"><a class="btn btn-danger me-2" href="{{ route('admin.dashboard') }}">👑</a></li>
                            @endif
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">🚪 Logout</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-3">
            <nav class="bg-custom text-white rounded shadow-sm p-3">
                <ul class="nav">
                    @foreach($menuCategories as $cat)
                        <li class="nav-item position-relative">
                        <a class="nav-link text-white" href="#">
                            {{ $cat->emoji ?? '📂' }} {{ $cat->name }}
                            @if($cat->children->count())
                                <span style="font-size: 0.8rem;">▼</span>
                            @endif
                        </a>

                            @if($cat->children->count())
                                <ul class="dropdown-menu-custom">
                                    @foreach($cat->children as $sub)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('category.show', [$sub->parent->slug, $sub->slug]) }}">
                                                📁 {{ $sub->emoji ?? $sub->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                    <li class="nav-item"><a class="btn btn-success me-2" href="{{ route('site.submit.form') }}">🚀 Submit website</a></li>
                </ul>
            </nav>
        </div>


    @if ($errors->any())
        <div class="container mt-2">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="container mt-2">
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

    <div class="main-container">
        @yield('content')
    </div>

    <footer class="py-5 border-top border-2 border-success mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-1"><a class="navbar-brand mb-4" href="/">Devil Engine | 👿🤘💰</a></h4>
                <p class="mb-0"><i>Where privacy meets reputation.</i></p>
            </div>
            <div class="col-md-4">
                <p class="mb-0">
                    <a class="navbar-brand" href="{{ route('rewards.info') }}">
                        🎁 Rewards
                    </a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('leaderboard') }}" class="navbar-brand">
                        🏆 Top Contributor
                    </a>
                </p>
                <p class="mb-0">
                    <a href="{{ route('ranks.info') }}" class="nav-link">
                        🎖️ Ranks
                    </a>
                </p>
                <p>📨 devilengine_666@protonmail.com</p>
            </div>
            <div class="col-md-4">
                <p class="float-end mb-1">
                    <a class="navbar-brand" href="#">⬆️ Back to top</a>
                </p>
            </div>
        </div>
    </div>
    </footer>

    </body>
</html>