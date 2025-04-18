<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SiteReport;
use App\Models\Site;

use App\Services\CaptchaService;

class SiteReportController extends Controller
{
    public function create(Site $site)
    {
        $captcha = app(CaptchaService::class)->generateCaptcha();
        return view('report-site', compact('site','captcha'));
    }

    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->route('site.report.form', $site->slug)->withErrors([
                'captcha' => 'Too much try, try again later.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->route('site.report.form', $site->slug)->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        SiteReport::create([
            'site_id' => $site->id,
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('site.show', $site->slug)->with('success', 'Thanks for your report. We’ll review it shortly.');
    }
}
