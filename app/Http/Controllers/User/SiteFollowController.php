<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteFollowController extends Controller
{
    public function index()
    {
        $sites = auth()->user()->followedSites()->with('category')->latest()->get();
        return view('user.follows.index', compact('sites'));
    }
}
