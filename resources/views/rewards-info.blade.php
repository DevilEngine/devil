@extends('layout.app')

@section('title', '🎁 Rewards & DevilCoins')

@section('content')
<div class="container my-5">
  <h1 class="mb-4">🎁 Rewards & DevilCoins</h1>

  <p class="lead">Earn <strong>DevilCoins</strong> by contributing to the platform. Unlock powerful rewards and exclusive badges as your reputation grows.</p>

  <hr class="my-4">

  <h3 class="mb-3">📈 How to Earn DevilCoins</h3>
  <ul class="list-group mb-4">
    <li class="list-group-item">✅ Submit a site (approved) → <strong>+10</strong> DevilCoins</li>
    <li class="list-group-item">📝 Write an approved review → <strong>+5</strong> DevilCoins</li>
    <li class="list-group-item">🗳 Vote on a site's trust → <strong>+1</strong> DevilCoin</li>
  </ul>

  <h3 class="mb-3">🎯 Rewards by DevilCoin Level</h3>
  <div class="table-responsive mb-5">
    <table class="table table-bordered align-middle text-center">
      <thead class="table-dark">
        <tr>
          <th>💰 DevilCoins</th>
          <th>🎁 Reward</th>
          <th>🔍 Effect</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>10</td>
          <td>🏅 Contributor Badge</td>
          <td>Visible badge in your profile & leaderboard</td>
        </tr>
        <tr>
          <td>50</td>
          <td>🧾 Extended Submission Limit</td>
          <td>Submit up to 3 sites pending approval</td>
        </tr>
        <tr>
          <td>100</td>
          <td>🔥 Free Homepage Feature (7 days)</td>
          <td>Promote your site on the homepage</td>
        </tr>
        <tr>
          <td>150</td>
          <td>📊 Access Site Stats</td>
          <td>Unlock private stats for your sites</td>
        </tr>
        <tr>
          <td>200</td>
          <td>👹 Demonic Elite Badge</td>
          <td>Prestige badge to show your infernal status</td>
        </tr>
        <tr>
          <td>300</td>
          <td>🎁 Free Banner Slot (7 days)</td>
          <td>Promote your banner for free</td>
        </tr>
        <tr>
          <td>500</td>
          <td>🛡️ Trusted User</td>
          <td>
            <ul class="list-unstyled mb-0">
              <li>✔ Reviews auto-approved</li>
              <li>✔ Trust votes x2 impact</li>
              <li>✔ Trusted badge 🛡️</li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <h3 class="mb-3">📝 Notes</h3>
  <ul>
    <li>Most rewards are unlocked automatically.</li>
    <li>Homepage features and banner slots are single-use (limited time).</li>
    <li>Your DevilCoins reflect your contribution and community trust.</li>
    <li>Trusted User status is recalculated dynamically.</li>
  </ul>

  <div class="mt-5">
    <a href="{{ route('dashboard.rewards') }}" class="btn btn-success">
      🚀 View My Rewards
    </a>
  </div>
</div>
@endsection
