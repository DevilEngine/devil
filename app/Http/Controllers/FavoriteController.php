<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;

class FavoriteController extends Controller
{
    public function toggle(Site $site)
    {
        $user = Auth::user();

        if ($user->hasFavorited($site)) {
            $user->favorites()->detach($site->id);
        } else {
            $user->favorites()->attach($site->id);
        }

        return back()->with('success', 'Favorites updated.');
    }

    public function index()
    {
        $sites = Auth::user()->favorites()->with(['category', 'tags'])->paginate(12);
        return view('favorites.index', compact('sites'));
    }
}
