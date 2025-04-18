<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorites = $user->favorites()->with('category')->take(6)->get();
        $reviews = $user->reviews()->latest()->take(5)->get();
        $submittedSites = $user->sites()->latest()->take(5)->get(); // si soumission activée

        $trustVotes = auth()->user()->trustVotes()->with('site')->latest()->take(10)->get();

        return view('user.dashboard', compact('favorites', 'reviews', 'submittedSites','trustVotes'));
    }

    public function rewards()
    {
        $user = auth()->user();
        $unlocked = $user->unlockedRewards();
        $next = $user->nextRewards();

        return view('user.rewards', compact('unlocked', 'next', 'user'));
    }
}
