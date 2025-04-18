<?php

namespace App\Http\Controllers;

use App\Models\User;

class PublicUserController extends Controller
{
    public function show(User $user)
    {
        $reviews = $user->reviews()->with('site')->latest()->take(10)->get();
        $submittedSites = $user->sites()->where('status', 'active')->latest()->get();

        return view('user-profile', compact('user', 'reviews', 'submittedSites'));
    }
}
