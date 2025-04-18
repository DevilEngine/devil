<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SiteReview;
use App\Models\Site;
use App\Models\Banner;
use App\Models\User;

use App\Services\CaptchaService;

use Spatie\Tags\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Pagination\LengthAwarePaginator;

use Auth;

class PublicController extends Controller
{
    public function index()
    {
        $categories = Category::with([
            'children.sites.reviews' => fn($q) => $q->where('approved', true),
            'children.sites.tags',
            'children.parent',
        ])->whereNull('parent_id')->orderBy('name')->get();
    
        $tags = Tag::select('tags.*')
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->groupBy('tags.id', 'tags.name', 'tags.slug', 'tags.type', 'tags.order_column', 'tags.created_at', 'tags.updated_at')
            ->orderByRaw('COUNT(taggables.taggable_id) DESC')
            ->take(20)
            ->get();
    
        $banners = Banner::active()->orderBy('position')->take(6)->get();
    
        $featuredSites = Site::with(['reviews' => fn($q) => $q->where('approved', true), 'tags', 'trustVotes'])
            ->where('featured_home', true)
            ->featuredHome()
            ->where('status', 'active')
            ->orderBy('name')
            ->take(4)
            ->get();
    
        // ✅ Suggestions personnalisées
        $suggestedSites = collect();
    
        if (auth()->check()) {
            $user = auth()->user();
    
            $tagCounts = collect();
    
            // Tags from reviews
            $reviewedTags = $user->reviews()
                ->with('site.tags')
                ->get()
                ->flatMap(fn($review) => $review->site?->tags ?? [])
                ->map(fn($tag) => $tag->name)
                ->countBy();
    
            $tagCounts = $tagCounts->mergeRecursive($reviewedTags);
    
            // Tags from trust votes
            $votedTags = $user->trustVotes()
                ->with('site.tags')
                ->get()
                ->flatMap(fn($vote) => $vote->site?->tags ?? [])
                ->map(fn($tag) => $tag->name)
                ->countBy();
    
            $tagCounts = $tagCounts->mergeRecursive($votedTags);
    
            $topTags = $tagCounts->sortDesc()->keys()->take(5)->toArray();
    
            // Exclude sites already reviewed or voted
            $excludedSiteIds = collect([
                ...$user->reviews()->pluck('site_id')->toArray(),
                ...$user->trustVotes()->pluck('site_id')->toArray(),
            ]);
    
            // Get suggested sites
            $suggestedSites = Site::with(['tags', 'reviews' => fn($q) => $q->where('approved', true)])
                ->withAnyTags($topTags)
                ->where('status', 'active')
                ->whereNotIn('id', $excludedSiteIds)
                ->orderByDesc('created_at')
                ->take(8)
                ->get();
        }
    
        return view('index', compact('categories', 'banners', 'featuredSites', 'tags', 'suggestedSites'));
    }    

    public function tag(Request $request, $slug)
    {
        $tag = Tag::findFromString($slug);
    
        if (!$tag) {
            abort(404);
        }
    
        $categoryId = $request->input('category_id');
        $status = $request->input('status');
        $darknetOnly = $request->filled('darknet');
    
        $categories = Category::with('children')->whereNull('parent_id')->get();
    
        // 🔥 Featured sites with this tag
        $featuredSites = Site::with(['category', 'reviews' => fn($q) => $q->where('approved', true), 'tags'])
            ->withAnyTags([$tag->name])
            ->where('featured_tag', true)
            ->where('status', 'active')
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId));
    
        if ($darknetOnly) {
            $featuredSites->where(function ($q) {
                $q->where('url', 'like', '%.onion%')
                  ->orWhere('mirror_1', 'like', '%.onion%')
                  ->orWhere('mirror_2', 'like', '%.onion%');
            });
        }
    
        $featuredSites = $featuredSites->take(4)->get();
        $excludedIds = $featuredSites->pluck('id');
    
        // 📄 Standard site list
        $sitesQuery = Site::with(['category', 'tags', 'reviews' => fn($q) => $q->where('approved', true)], 'trustVotes')
            ->withCount(['reviews as approved_reviews_count' => fn($q) => $q->where('approved', true)])
            ->where('status', 'active')
            ->withAnyTags([$tag->name])
            ->whereNotIn('id', $excludedIds)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($status, fn($q) => $q->where('status', $status));
    
        // 🌑 Apply darknet filter
        if ($darknetOnly) {
            $sitesQuery->where(function ($q) {
                $q->where('url', 'like', '%.onion%')
                  ->orWhere('mirror_1', 'like', '%.onion%')
                  ->orWhere('mirror_2', 'like', '%.onion%');
            });
        }
    
        $sites = $sitesQuery
            ->orderByRaw('featured DESC')
            ->orderByDesc('approved_reviews_count')
            ->orderBy('name')
            ->get();
    
        // 🔁 Tri par score de confiance
        $sorted = $sites->sortByDesc(fn($site) => $site->trustScoreCached())->values();
    
        // 📦 Pagination manuelle
        $perPage = 12;
        $page = request('page', 1);
        $pagedSites = new LengthAwarePaginator(
            $sorted->forPage($page, $perPage),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    
        return view('tags', compact(
            'tag',
            'sites',
            'categories',
            'categoryId',
            'status',
            'featuredSites',
            'pagedSites'
        ));
    }       

    public function showSite($slug)
    {
        $site = Site::with(['category', 'reviews' => fn($q) => $q->where('approved', true), 'trustVotes'])
                ->where('status', 'active')
                ->where('slug', $slug)
                ->firstOrFail();
                
        
        $average = round($site->averageRating(), 1);
        $captcha = app(CaptchaService::class)->generateCaptcha();

        return view('website', compact('site', 'average','captcha'));
    }

    public function storeReview(Request $request, $slug): RedirectResponse
    {
        $site = Site::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

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

        SiteReview::create(array_merge($validated, [
            'site_id' => $site->id,
            'user_id' => Auth::id(),
            'approved' => auth()->user()->isTrustedUser(), // par défaut
        ]));

        if (auth()->user()->isTrustedUser()) {
            auth()->user()->updateReputation();
        }

        return redirect()->route('site.show', $site->slug)
            ->with('success', 'Thanks for your rating, a moderator will be to verify your review');
    }

    public function darknet()
    {
        $sites = Site::with(['category', 'tags', 'trustVotes'])
            ->where('status', 'active')
            ->darknet()
            ->latest()
            ->paginate(12);

        return view('darknet', compact('sites'));
    }

    public function category(Request $request, $parent, $child)
    {
        $category = Category::where('slug', $child)
            ->whereHas('parent', function ($q) use ($parent) {
                $q->where('slug', $parent);
            })
            ->with('parent')
            ->firstOrFail();
    
        $tag = $request->input('tag');
        $status = $request->input('status');
        $darknetOnly = $request->filled('darknet');
    
        $tags = Tag::orderBy('name')->pluck('name');
    
        // Featured sites
        $featuredSites = Site::with(['reviews' => fn($q) => $q->where('approved', true), 'tags', 'trustVotes'])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->where('featured_category', true)
            ->orderBy('name')
            ->take(4)
            ->get();
    
        $featuredIds = $featuredSites->pluck('id');
    
        // Base query
        $sitesQuery = Site::with([
                'reviews' => fn($q) => $q->where('approved', true),
                'tags',
                'trustVotes'
            ])
            ->withCount(['reviews as approved_reviews_count' => fn($q) => $q->where('approved', true)])
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->whereNotIn('id', $featuredIds)
            ->when($tag, fn($q) => $q->withAnyTags([$tag]))
            ->when($status, fn($q) => $q->where('status', $status));
    
        // ✅ Ajout du filtre darknet
        if ($darknetOnly) {
            $sitesQuery->where(function ($q) {
                $q->where('url', 'like', '%.onion%')
                  ->orWhere('mirror_1', 'like', '%.onion%')
                  ->orWhere('mirror_2', 'like', '%.onion%');
            });
        }
    
        // Tri standard + récupération
        $sites = $sitesQuery
            ->orderByRaw('featured DESC')
            ->orderByDesc('approved_reviews_count')
            ->orderBy('name')
            ->get();
    
        // Tri par confiance
        $sorted = $sites->sortByDesc(fn($site) => $site->trustScoreCached())->values();
    
        // Pagination manuelle
        $perPage = 12;
        $page = $request->input('page', 1);
        $pagedSites = new LengthAwarePaginator(
            $sorted->forPage($page, $perPage),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        return view('category', compact('category', 'sites', 'tags', 'tag', 'status', 'featuredSites', 'pagedSites'));
    }
    
       

    public function search(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category_id');
        $tag = $request->input('tag');
        $status = $request->input('status');

        $categories = Category::with('children')
                    ->whereNull('parent_id')
                    ->orderBy('name')
                    ->get();

        $tags = Tag::orderBy('name')->pluck('name');

        $sites = Site::with(['category', 'tags', 'reviews' => fn($q) => $q->where('approved', true)],'trustVotes')
        ->withCount(['reviews as approved_reviews_count' => fn($q) => $q->where('approved', true)])
        ->where('status', 'active')
        ->when($query, function ($q) use ($query) {
            $q->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('url', 'like', "%{$query}%")
                    ->orWhereHas('category', fn($cat) => $cat->where('name', 'like', "%{$query}%"))
                    ->orWhereHas('tags', fn($tag) => $tag->where('name', 'like', "%{$query}%"));
            });
        })
        ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
        ->when($tag, fn($q) => $q->withAnyTags([$tag]))
        ->when($status, fn($q) => $q->where('status', $status))
        ->orderByRaw('featured DESC')
        ->orderByDesc('approved_reviews_count')
        ->orderBy('name');

        if ($request->filled('darknet')) {
            $sites->darknet();
        }

        $sites = $sites->get();

        // Tri par score de confiance (en mémoire)
        $sorted = $sites->sortByDesc(fn($site) => $site->trustScoreCached())->values();

        // Pagination manuelle
        $perPage = 12;
        $page = request('page', 1);
        $pagedSites = new LengthAwarePaginator(
            $sorted->forPage($page, $perPage),
            $sorted->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    
        return view('search', compact('sites', 'query', 'categories', 'categoryId', 'tags', 'tag', 'status','pagedSites'));
    }

    public function showSubmitForm()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $tags = Tag::orderBy('name')->pluck('name');
        $captcha = app(CaptchaService::class)->generateCaptcha();
        return view('submit-site', compact('categories', 'tags','captcha'));
    }

    public function submitSite(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'mirror_1' => 'nullable|url|max:255',
            'mirror_2' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array|max:6', // ✅ max 6 tags
            'tags.*' => 'string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = auth()->id();

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

        $pendingLimit = auth()->user()->hasExtendedSubmissionLimit() ? 3 : 1;

        $pendingCount = auth()->user()->sites()
            ->where('status', 'pending') // ou status = "inactive" si c’est ta convention
            ->count();

        if ($pendingCount >= $pendingLimit) {
            return redirect()->back()->withErrors([
                'You can only have up to ' . $pendingLimit . ' site(s) pending approval at the same time.',
            ])->withInput();
        }
        
    
        // Vérifie qu'on a bien une sous-catégorie
        $category = Category::findOrFail($validated['category_id']);
        if (is_null($category->parent_id)) {
            throw ValidationException::withMessages([
                'category_id' => 'Please choose a sub-category, not a parent category.',
            ]);
        }
    
        // Slug unique
        $slug = \Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Site::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $validated['slug'] = $slug;
    
        // Upload logo
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }
    
        $validated['status'] = 'inactive'; // site soumis mais non encore approuvé
    
        // Création du site
        $site = Site::create($validated);
    
        // Ne garder que les tags existants
        $selectedTags = collect($request->input('tags', []))
            ->map(fn($t) => trim($t))
            ->filter();
    
        $availableTags = Tag::pluck('name')->toArray();
        $validTags = $selectedTags->filter(fn($t) => in_array($t, $availableTags))->unique();
    
        $site->attachTags($validTags);
    
        return redirect()->route('site.submit.form')->with('success', 'Thank you! Your site has been submitted and is pending review.');
    }
    

    public function leaderboard()
    {
        $topUsers = User::where('reputation', '>', 0)
            ->orderByDesc('reputation')
            ->take(50)
            ->get();

        return view('leaderboard', compact('topUsers'));
    }
    
}
