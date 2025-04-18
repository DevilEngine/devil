<?php

namespace App\Http\Controllers\User;

use App\Models\Site;
use App\Models\SiteClaim;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\CaptchaService;

use Auth;

class SiteClaimController extends Controller
{

    public function index()
    {
        $claims = SiteClaim::with('site')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.claims.index', compact('claims'));
    }

    // Formulaire de réclamation
    public function create($slug)
    {
        $site = Site::where('slug', $slug)->firstOrFail();
        $captcha = app(CaptchaService::class)->generateCaptcha();

        // Vérifie que l'utilisateur ne possède pas déjà ce site
        if ($site->user_id === Auth::id()) {
            return redirect()->route('site.show', $site->slug)
                ->with('info', 'You already own this site.');
        }

        return view('user.claims.create', compact('site','captcha'));
    }

    // Soumission de la réclamation
    public function store(Request $request, $slug)
    {
        $site = Site::where('slug', $slug)->firstOrFail();

        $request->validate([
            'proof_type'     => 'required|in:meta,file,email,other',
            'proof_details'  => 'required|string|max:1000',
            'message'        => 'nullable|string|max:1000',
        ]);

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->route('site.claim.form')->withErrors([
                'captcha' => 'Too much try, try again later.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->route('site.claim.form')->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        // Vérifie si une réclamation est déjà en attente
        $already = SiteClaim::where('site_id', $site->id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($already) {
            return redirect()->route('site.show', $site->slug)
                ->with('warning', 'You already submitted a claim for this site.');
        }

        // Enregistre la demande
        SiteClaim::create([
            'site_id'       => $site->id,
            'user_id'       => Auth::id(),
            'proof_type'    => $request->proof_type,
            'proof_details' => $request->proof_details,
            'message'       => $request->message,
            'status'        => 'pending',
        ]);

        return redirect()->route('site.show', $site->slug)
            ->with('success', 'Your claim has been submitted and is under review.');
    }
}

