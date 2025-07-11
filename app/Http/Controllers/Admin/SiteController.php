<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\Category;

use Auth;
use Storage;

class SiteController extends Controller
{

    public function index()
    {
        $sites = Site::with('category')->latest()->paginate(10);
        return view('admin.sites.index', compact('sites'));
    }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        return view('admin.sites.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'mirror_1' => 'nullable|url|max:255',
            'mirror_2' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'status' => 'required|in:active,inactive,scam',
        ]);
    
        $validated['featured'] = $request->has('featured');
        $validated['featured_home'] = $request->has('featured_home');
        $validated['featured_category'] = $request->has('featured_category');
        $validated['featured_tag'] = $request->has('featured_tag');
        $validated['no_kyc'] = $request->has('no_kyc');
        $validated['has_onion'] = $request->has('has_onion');
        $validated['decentralized'] = $request->has('decentralized');
        $validated['smart_contract'] = $request->has('smart_contract');
        $validated['user_id'] = Auth::id();
    
        // Vérification que la catégorie est bien une sous-catégorie
        $category = Category::findOrFail($validated['category_id']);
        if (is_null($category->parent_id)) {
            throw ValidationException::withMessages([
                'category_id' => 'You need to choose a sub-category, not a parent category.',
            ]);
        }
    
        // Logo
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }
    
        // Création du site
        $site = Site::create($validated);
    
        // Tags
        $tags = collect(explode(',', $request->input('tags')))
            ->map(fn($t) => trim($t))
            ->filter()
            ->unique();
    
        $site->attachTags($tags);
    
        return redirect()->route('admin.sites.index')->with('success', 'Site added successfully.');
    }
    

    public function edit(Site $site)
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        return view('admin.sites.edit', compact('site', 'categories'));
    }

    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'mirror_1' => 'nullable|url|max:255',
            'mirror_2' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'status' => 'required|in:active,inactive,scam',
        ]);

        $validated['featured'] = $request->has('featured');
        $validated['featured_home'] = $request->has('featured_home');
        $validated['featured_category'] = $request->has('featured_category');
        $validated['featured_tag'] = $request->has('featured_tag');
        $validated['no_kyc'] = $request->has('no_kyc');
        $validated['has_onion'] = $request->has('has_onion');
        $validated['decentralized'] = $request->has('decentralized');
        $validated['smart_contract'] = $request->has('smart_contract');

        $category = Category::findOrFail($validated['category_id']);
        if (is_null($category->parent_id)) {
            throw ValidationException::withMessages([
                'category_id' => 'You need to choose sub category, not a parent category.',
            ]);
        }

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $site->update($validated);

        $tags = collect(explode(',', $request->input('tags')))
            ->map(fn($t) => trim($t))
            ->filter()
            ->unique();

        $site->syncTags($tags);

        return redirect()->route('admin.sites.index')->with('success', 'Site mis à jour.');
    }

    public function destroy(Site $site)
    {
        if ($site->logo_path && Storage::disk('public')->exists($site->logo_path)) {
            Storage::disk('public')->delete($site->logo_path);
        }

        $site->delete();

        return redirect()->route('admin.sites.index')->with('success', 'Site supprimé.');
    }

    public function featured()
    {
        $sites = Site::where(function ($query) {
            $query->where('featured_home', true)
                ->orWhere('featured_category', true)
                ->orWhere('featured_tag', true);
        })->with('category')->orderBy('name')->get();

        return view('admin.sites.featured', compact('sites'));
    }
    
    public function toggleFeatured(Request $request, Site $site)
    {
        $request->validate([
            'field' => 'required|in:featured_home,featured_category,featured_tag',
        ]);

        $field = $request->input('field');

        $site->$field = false;
        $site->featured = false;
        $site->save();

        return redirect()->route('admin.sites.featured')->with('success', 'Site updated.');
    }

    public function pending()
    {
        $sites = Site::where('status', 'inactive')->with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.sites.pending', compact('sites'));
    }

    public function approve(Site $site)
    {
        $site->status = 'active';
        $site->save();

        if ($site->user) {
            $site->user->updateReputation();
        }

        return redirect()->route('admin.sites.pending')->with('success', 'Site approved and published.');
    }

}
