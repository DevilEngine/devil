<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Category;
use App\Models\SiteReview;
use App\Models\User;
use App\Models\Banner;
use App\Models\SiteReport;

use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        $year = now()->year;

        $pendingReports = SiteReport::where('resolved', false)->count();
    
        // Stats mensuelles pour l’année en cours
        $monthlySites = Site::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('count', 'month');
    
        $monthlyReviews = SiteReview::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('count', 'month');
    
        // Génère 12 mois complets
        $months = collect(range(1, 12))->map(function ($m) use ($monthlySites, $monthlyReviews) {
            return [
                'label' => Carbon::create()->month($m)->format('M'),
                'sites' => $monthlySites->get($m, 0),
                'reviews' => $monthlyReviews->get($m, 0),
            ];
        });

        $usersCount = User::count();
        $bannersCount = Banner::where('active', true)->count();
        $featuredSitesCount = Site::where('featured', true)->count();
    
        return view('admin.dashboard', [
            'totalSites' => Site::count(),
            'totalCategories' => Category::whereNull('parent_id')->count(),
            'totalSubCategories' => Category::whereNotNull('parent_id')->count(),
            'pendingReviews' => SiteReview::where('approved', false)->count(),
            'approvedReviews' => SiteReview::where('approved', true)->count(),
            'months' => $months,
            'usersCount' => $usersCount,
            'bannersCount' => $bannersCount,
            'featuredSitesCount' => $featuredSitesCount,
            'pendingReports' => $pendingReports
        ]);
    }
    
}
