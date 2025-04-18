<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=1000,min_height=250,ratio=4/1',
            'url' => 'required|url',
            'position' => 'required|integer|min:1|max:6|unique:banners,position',
        ]);

        $validated['image_path'] = $request->file('image')->store('banners', 'public');
        $validated['active'] = $request->has('active');

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Bannière ajoutée avec succès.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=1000,min_height=250,ratio=4/1',
            'url' => 'required|url',
            'position' => 'required|integer|min:1|max:6|unique:banners,position,' . $banner->id,
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $validated['active'] = $request->has('active');

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Bannière mise à jour.');
    }

    public function destroy(Banner $banner)
    {
        // Supprimer l'image associée
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Bannière supprimée.');
    }

}
