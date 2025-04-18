<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerRequestController extends Controller
{
    public function index()
    {
        $requests = BannerRequest::with('user', 'site')
            ->latest()
            ->get();

        return view('admin.banners.requests', compact('requests'));
    }

    public function approve(Request $request, $req)
    {

        $bannerRequest = BannerRequest::where('id', $req)->firstOrFail();

        if ($bannerRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $expiresAt = match ($bannerRequest->duration) {
            '1_week'  => now()->addWeek(),
            '2_weeks' => now()->addWeeks(2),
            '1_month' => now()->addMonth(),
            default   => now()->addWeek(), // fallback
        };

        // Détermination de l'URL finale
        $finalUrl = $bannerRequest->external_url ?? ($bannerRequest->site ? $bannerRequest->site->url : null);

        if (!$finalUrl) {
            return back()->with('error', 'No valid URL found for this banner.');
        }

        // Créer la bannière
        Banner::create([
            'title'     => $bannerRequest->title ?? 'Banner promo from '.$bannerRequest->user->username.'',
            'url'       => $finalUrl,
            'image_path'=> $bannerRequest->image,
            'position'  => $bannerRequest->position,
            'active'    => true,
            'site_id'   => $bannerRequest->site_id,
            'user_id'   => $bannerRequest->user_id,
            'external_url' => $bannerRequest->external_url,
            'expires_at'=> $expiresAt
        ]);

        $bannerRequest->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Banner request approved and published.');
    }

    public function reject(Request $request, $req)
    {

        $bannerRequest = BannerRequest::where('id', $req)->firstOrFail();

        if ($bannerRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $bannerRequest->update([
            'status' => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);

        return back()->with('success', 'Banner request rejected.');
    }
}
