<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Site;
use App\Models\RewardUsage;
use App\Models\DevilCoinUsage;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Services\CaptchaService;

use Auth;

class SiteController extends Controller
{
    public function index()
    {
        $sites = auth()->user()->sites()->latest()->get();
        return view('user.sites.index', compact('sites'));
    }

    public function edit($id)
    {
        $site = Site::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $categories = \App\Models\Category::with('children')->whereNull('parent_id')->get();
        $captcha = app(CaptchaService::class)->generateCaptcha();
        $tags = \Spatie\Tags\Tag::all();
        return view('user.sites.edit', compact('site', 'categories', 'tags','captcha'));
    }

    public function update(Request $request, $id)
    {
        $site = Site::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'mirror_1' => 'nullable|url|max:255',
            'mirror_2' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive,scam',
            'logo' => 'nullable|image|max:2048'
        ]);

        // Si l'utilisateur utilise son slot bannière, l'image est requise
        if ($request->has('use_banner_slot')) {
            $validated['banner_image'] = 'required|image|dimensions:min_width=800,min_height=200|max:2048';
        }

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->back()->withErrors([
                'captcha' => 'Too much try, try again later.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->back()->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        $category = \App\Models\Category::find($validated['category_id']);
        if (is_null($category->parent_id)) {
            throw ValidationException::withMessages([
                'category_id' => 'Please select a sub-category.',
            ]);
        }

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si présent
            if ($site->logo_path && \Storage::disk('public')->exists($site->logo_path)) {
                \Storage::disk('public')->delete($site->logo_path);
            }
        
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        // Bonus feature
        if ($request->has('use_feature_bonus') && auth()->user()->free_feature_home > 0) {
            $validated['featured'] = true;
            $validated['featured_home'] = true;
            $validated['featured_home_until'] = now()->addDays(7);
            auth()->user()->decrement('free_feature_home');
        
            RewardUsage::create([
                'user_id' => auth()->id(),
                'site_id' => $site->id,
                'reward_key' => 'feature_home',
                'label' => 'Free Homepage Feature',
            ]);
        }

        if ($request->has('use_banner_slot') && auth()->user()->free_banner_slots > 0) {
            $bannerPath = $request->file('banner_image')->store('banners', 'public');
        
            \App\Models\Banner::create([
                'title' => $site->name,
                'url' => $site->url,
                'image' => $bannerPath,
                'position' => null,
                'active' => true,
                'site_id' => $site->id,
                'user_id' => auth()->id(),
                'expires_at' => now()->addDays(7),
            ]);
        
            auth()->user()->decrement('free_banner_slots');
        
            \App\Models\RewardUsage::create([
                'user_id' => auth()->id(),
                'site_id' => $site->id,
                'reward_key' => 'banner_slot',
                'label' => 'Free Banner Slot (7 days)',
            ]);
        }
        

        $site->update($validated);

        // Tags
        $tags = collect(explode(',', $request->input('tags')))->map(fn($t) => trim($t))->filter()->unique();
        $site->syncTags($tags);

        return redirect()->route('sites.index')->with('success', 'Site updated.');
    }


    public function featureMultiple(Request $request, Site $site)
    {
        $user = auth()->user();
    
        if ($site->user_id !== $user->id) {
            abort(403);
        }

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->back()->withErrors([
                'captcha' => 'Too much try, try again later.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->back()->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();
    
        $features = [
            'home'     => ['field' => 'featured_home',     'until' => 'featured_home_until',     'cost' => 150],
            'category' => ['field' => 'featured_category', 'until' => 'feature_category_until', 'cost' => 100],
            'tag'      => ['field' => 'featured_tag',      'until' => 'feature_tag_until',      'cost' => 75],
        ];
    
        $selected = collect($features)->filter(fn($_, $key) => $request->has($key));
    
        if ($selected->isEmpty()) {
            return back()->with('error', 'You must choose at least one feature.');
        }
    
        $totalCost = $selected->sum('cost');
    
        if ($user->availableDevilCoins() < $totalCost) {
            return back()->with('error', "You don't have enough DevilCoins. Needed: $totalCost");
        }
    
        $user->increment('coins_spent', $totalCost);
    
        foreach ($selected as $option) {
            $site->{$option['field']} = true;
            $site->featured = true;
            $site->{$option['until']} = now()->addDays(7);
        }
    
        $site->save();
    
        DevilCoinUsage::create([
            'description' => 'Website featured '.$site->name.'',
            'user_id' => auth()->id(),
            'amount' => $totalCost,
        ]);
    
        return back()->with('success', 'Your site has been featured successfully for 7 days.');
    }


    protected function authorizeAccess(Site $site)
    {
        if ($site->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
