<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteFollowController extends Controller
{
    public function toggle(Site $site)
    {
        $user = auth()->user();

        if ($user->followedSites()->where('site_id', $site->id)->exists()) {
            $user->followedSites()->detach($site->id);
            return back()->with('success', 'You unfollowed this site.');
        } else {
            $user->followedSites()->attach($site->id);
            return back()->with('success', 'You are now following this site.');
        }
    }
}
