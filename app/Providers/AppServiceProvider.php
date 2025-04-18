<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use App\Models\Category;
use App\Models\SiteClaim;
use App\Models\Site;
use App\Models\SiteReport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $menuCategories = Category::with('children')
                ->whereNull('parent_id')
                ->orderBy('name')
                ->get();

            $view->with('menuCategories', $menuCategories);
        });

        View::composer('layout.admin', function ($view) {
            $pendingClaimsCount = SiteClaim::where('status', 'pending')->count();
            $pendingSitesCount = Site::where('status', 'pending')->count();
            $pendingReportsCount = SiteReport::where('resolved', '0')->count();
    
            $view->with([
                'pendingClaimsCount' => $pendingClaimsCount,
                'pendingSitesCount' => $pendingSitesCount,
                'pendingReportsCount' => $pendingReportsCount,
            ]);
        });
    }
}
