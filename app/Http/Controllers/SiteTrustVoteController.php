<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use App\Models\SiteTrustVote;

class SiteTrustVoteController extends Controller
{
    public function vote(Request $request, Site $site)
    {
        $request->validate([
            'trusted' => 'required|boolean',
        ]);

        $user = auth()->user();

        // Création ou mise à jour du vote
        SiteTrustVote::updateOrCreate(
            ['user_id' => $user->id, 'site_id' => $site->id],
            ['trusted' => $request->boolean('trusted')]
        );

        $user->updateReputation();

        return back()->with('success', 'Your trust vote has been recorded.');
    }
}
