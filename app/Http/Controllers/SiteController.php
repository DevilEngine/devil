<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Category;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::with('category')->latest()->paginate(10);
        return view('sites.index', compact('sites'));
    }
}
