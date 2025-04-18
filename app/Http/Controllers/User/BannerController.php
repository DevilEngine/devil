<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = auth()->user()->banners()->with('site')->latest()->get();
        return view('user.banners.index', compact('banners'));
    }

    public function destroy(Banner $banner)
    {
        $this->authorizeAccess($banner);

        // Supprimer l'image si stockée localement
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner deleted successfully.');
    }

    protected function authorizeAccess(Banner $banner)
    {
        if ($banner->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
