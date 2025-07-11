<div class="list-group mb-4">
  <a href="{{ route('user.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
    👤 Dashboard
  </a>
  <a href="{{ route('dashboard.rewards') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.rewards') ? 'active' : '' }}">
    🎁 My Rewards
  </a>
  <a href="{{ route('devilcoins.history') }}" class="list-group-item list-group-item-action {{ request()->routeIs('devilcoins.history') ? 'active' : '' }}">
    💳 DevilCoin Purchases
  </a>
  <a href="{{ route('devilcoin.usage.all') }}" class="list-group-item list-group-item-action {{ request()->routeIs('devilcoin.usage.all') ? 'active' : '' }}">
    💸 DevilCoin Usage
  </a>
  <a href="{{ route('user.withdrawals.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.withdrawals.*') ? 'active' : '' }}">
    💸 Withdraw DevilCoins
  </a>
  <a href="{{ route('banners.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('banners.*') ? 'active' : '' }}">
    📢 My Banners
  </a>
  <a href="{{ route('dashboard.banner-request.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.banner-request.index') ? 'active' : '' }}">
    📋 My Banner Requests
  </a>
  <a href="{{ route('favorites.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('favorites.index') ? 'active' : '' }}">
    ⭐ Favorites
  </a>
  <a href="{{ route('sites.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('sites.index') ? 'active' : '' }}">
    📂 My Sites
  </a>
  <a href="{{ route('site.submit.form') }}" class="list-group-item list-group-item-action {{ request()->routeIs('site.submit.form') ? 'active' : '' }}">
    📤 Submit Site
  </a>
  <a href="{{ route('user.claims.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.claims.index') ? 'active' : '' }}">
    🙋 My Site Claims
  </a>
  <a href="{{ route('follows.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('follows.index') ? 'active' : '' }}">
    📌 Followed Sites
  </a>
  <a href="{{ route('user.profile.edit') }}" class="list-group-item list-group-item-action {{ request()->routeIs('user.profile.*') ? 'active' : '' }}">
    ✏️ Edit Profile
  </a>
</div>
