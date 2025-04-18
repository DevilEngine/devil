<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Site;

class SiteStatsController extends Controller
{
    public function show(Site $site)
    {
        $user = auth()->user();

        // Vérifie propriété du site
        if ($site->user_id !== $user->id) {
            abort(403);
        }

        // Vérifie qu'il a la récompense (≥ 150 DevilCoins)
        if ($user->reputation - $user->coins_spent < 150) {
            return redirect()->route('sites.index')
                ->with('error', 'You need 150 DevilCoins to unlock site stats.');
        }

        // Stats
        $totalReviews     = $site->reviews()->count();
        $approvedReviews  = $site->reviews()->where('approved', true)->count();
        $avgRating        = $site->reviews()->where('approved', true)->avg('rating');
        $followersCount   = $site->followers()->count();

        return view('user.sites.stats', compact('site', 'totalReviews', 'approvedReviews', 'avgRating', 'followersCount'));
    }
}
