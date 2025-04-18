<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;

use App\Services\CaptchaService;

class BannerRequestController extends Controller
{
    public function create()
    {
        $captcha = app(CaptchaService::class)->generateCaptcha();
        $sites = Site::where('status', 'active')->orderBy('name')->get();
        $usedPositions = Banner::where('active', true)->pluck('position')->toArray();
        return view('user.banners.create', compact('sites','captcha','usedPositions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'nullable|exists:sites,id',
            'external_url' => 'nullable|url',
            'title'     => 'nullable|string|max:255',
            'position' => 'required|integer|min:1|max:6',
            'image'     => 'required|image|mimes:jpg,jpeg,png|max:2048|dimensions:min_width=800,min_height=200',
            'duration' => 'required|in:1_week,2_weeks,1_month',

        ]);

        $alreadyUsed = Banner::where('active', true)
            ->where('position', $request->input('position'))
            ->exists();

        if ($alreadyUsed) {
            return back()->withErrors([
                'position' => 'This position is already taken by an active banner. Please choose another slot.'
            ])->withInput();
        }

        $cost = match ($validated['duration']) {
            '1_week'  => 1000,
            '2_weeks' => 1500,
            '1_month' => 2000,
        };

        if (!$request->filled('site_id') && !$request->filled('external_url')) {
            return back()->withErrors(['site_id' => 'Please select a site or provide an external URL.']);
        }

        $user = auth()->user();

        if ($user->reputation < $cost) {
            return back()->with('error', "You need {$cost} DevilCoins to submit this request.");
        }

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->route('banners.index')->withErrors([
                'captcha' => 'Too much try, try again later.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->route('banners.index')->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        $imagePath = $request->file('image')->store('banners/requests', 'public');

        BannerRequest::create([
            'user_id'  => auth()->id(),
            'site_id'  => $validated['site_id'],
            'external_url'  => $validated['external_url'],
            'title'    => $validated['title'],
            'image'    => $imagePath,
            'position' => $validated['position'],
            'status'   => 'pending',
            'duration' => $validated['duration'],
        ]);

        $user->increment('coins_spent', $cost);

        \App\Models\RewardUsage::create([
            'user_id'    => $user->id,
            'site_id'    => $validated['site_id'],
            'reward_key' => 'custom_banner_slot',
            'label'      => 'Paid banner request ('.str_replace('_', ' ', $validated['duration']).')',
        ]);

        \App\Models\DevilcoinUsage::create([
            'user_id'    => $user->id,
            'description'=> 'Banner request (' . str_replace('_', ' ', $validated['duration']) . ')',
            'amount'     => $cost,
        ]);

        return redirect()->route('banner-request.create')->with('success', 'Your banner request has been submitted and will be reviewed shortly.');
    }

    public function index()
    {
        $requests = auth()->user()
            ->bannerRequests()
            ->with('site')
            ->latest()
            ->get();

        return view('user.banners.requests', compact('requests'));
    }
}
