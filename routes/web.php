<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SiteController as AdminSiteController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

Route::get('/', [App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/category/{parent}/{child}', [App\Http\Controllers\PublicController::class, 'category'])->name('category.show');
Route::get('/search', [App\Http\Controllers\PublicController::class, 'search'])->name('search');
Route::view('/ranks', 'ranks')->name('ranks.info');

Route::get('/tags/{slug}', [App\Http\Controllers\PublicController::class, 'tag'])->name('tag.show');

Route::get('/website/{slug}', [App\Http\Controllers\PublicController::class, 'showSite'])->name('site.show');

// LOGIN/REGISTER/CAPTCHA ROUTE
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.action');
Route::get('/register/mnemonic', [App\Http\Controllers\AuthController::class, 'mnemonic'])->name('register.mnemonic');
Route::post('/register/mnemonic/confirm', [App\Http\Controllers\AuthController::class, 'saveMnemonic'])->name('register.mnemonic.confirm');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.action');

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/captcha', [App\Http\Controllers\AuthController::class, 'generateCaptcha']);

Route::get('/recover', [App\Http\Controllers\AuthController::class, 'showRecoverForm'])->name('recover');
Route::post('/recover', [App\Http\Controllers\AuthController::class, 'recover'])->name('recover.action');

Route::get('/user/{user:slug}', [\App\Http\Controllers\PublicUserController::class, 'show'])->name('user.public');

Route::get('/darknet', [\App\Http\Controllers\PublicController::class, 'darknet'])->name('darknet');
Route::get('/leaderboard', [\App\Http\Controllers\PublicController::class, 'leaderboard'])->name('leaderboard');

Route::view('/rewards-info', 'rewards-info')->name('rewards.info');

//Route::post('/nowpayments/webhook', [App\Http\Controllers\API\NowPaymentsWebhookController::class, 'handle'])->name('nowpayments.webhook');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/dashboard/rewards', [\App\Http\Controllers\User\DashboardController::class, 'rewards'])->name('dashboard.rewards');

    Route::post('/favorites/{site}', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/my-favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');

    Route::post('/sites/{site}/trust-vote', [\App\Http\Controllers\SiteTrustVoteController::class, 'vote'])->name('sites.trust.vote');

    Route::get('/submit-site', [App\Http\Controllers\PublicController::class, 'showSubmitForm'])->name('site.submit.form');
    Route::post('/submit-site', [App\Http\Controllers\PublicController::class, 'submitSite'])->name('site.submit');
    Route::get('/sites', [\App\Http\Controllers\User\SiteController::class, 'index'])->name('sites.index');
    Route::get('/sites/{site}/edit', [\App\Http\Controllers\User\SiteController::class, 'edit'])->name('sites.edit');
    Route::put('/sites/{id}', [\App\Http\Controllers\User\SiteController::class, 'update'])->name('sites.update');

    Route::post('/website/{slug}/review', [App\Http\Controllers\PublicController::class, 'storeReview'])->name('site.review');

    Route::get('/dashboard/profile', [\App\Http\Controllers\User\ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::post('/dashboard/profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('user.profile.update');

    Route::get('/site/{site:slug}/report', [\App\Http\Controllers\SiteReportController::class, 'create'])->name('site.report.form');
    Route::post('/site/{site:slug}/report', [\App\Http\Controllers\SiteReportController::class, 'store'])->name('site.report.submit');

    Route::post('/site/{site:slug}/follow', [\App\Http\Controllers\SiteFollowController::class, 'toggle'])->name('site.follow');
    Route::get('/follows', [\App\Http\Controllers\User\SiteFollowController::class, 'index'])->name('follows.index');

    Route::get('/sites/{site}/stats', [\App\Http\Controllers\User\SiteStatsController::class, 'show'])->name('sites.stats');

    Route::get('/banners', [\App\Http\Controllers\User\BannerController::class, 'index'])->name('banners.index');
    Route::delete('/banners/{banner}', [\App\Http\Controllers\User\BannerController::class, 'destroy'])->name('banners.destroy');

    Route::get('/banner-request', [\App\Http\Controllers\User\BannerRequestController::class, 'create'])->name('banner-request.create');
    Route::post('/banner-request', [\App\Http\Controllers\User\BannerRequestController::class, 'store'])->name('banner-request.store');
    Route::get('/my-banner-requests', [\App\Http\Controllers\User\BannerRequestController::class, 'index'])->name('dashboard.banner-request.index');

    Route::get('/devilcoin/usage/all', [\App\Http\Controllers\User\ProfileController::class, 'usage'])->name('devilcoin.usage.all');
    Route::get('/devilcoin/buy', [\App\Http\Controllers\User\DevilcoinPurchaseController::class, 'index'])->name('devilcoins.buy');
    Route::post('/devilcoins/checkout', [\App\Http\Controllers\User\DevilcoinPurchaseController::class, 'checkout'])->name('devilcoins.checkout');
    Route::get('/devilcoins/history', [\App\Http\Controllers\User\DevilcoinPurchaseController::class, 'history'])->name('devilcoins.history');
    Route::get('/devilcoins/payment/{purchase}', [\App\Http\Controllers\User\DevilcoinPurchaseController::class, 'paymentPage'])->name('devilcoins.payment');

    Route::post('/sites/{site}/feature', [\App\Http\Controllers\User\SiteController::class, 'featureMultiple'])->name('sites.feature.multi');
    //Route::post('/site/{site:slug}/claim-request', [\App\Http\Controllers\User\SiteClaimController::class, 'store'])->name('site.claim.request');

    Route::get('/site/{site}/claim/create', [\App\Http\Controllers\User\SiteClaimController::class, 'create'])->name('site.claim.form');
    Route::post('/site/{site}/claim', [\App\Http\Controllers\User\SiteClaimController::class, 'store'])->name('site.claim.submit');

    Route::get('/my-claims', [App\Http\Controllers\User\SiteClaimController::class, 'index'])->name('user.claims.index');

    Route::get('/sites/{site}/feature', function (\App\Models\Site $site) { 
        abort_unless($site->user_id === auth()->id(), 403);
        $captcha = app(App\Services\CaptchaService::class)->generateCaptcha();
        return view('user.sites.feature', compact('site','captcha')); 
    })->name('sites.feature.page');

});

Route::middleware(['auth', 'is_admin'])->prefix('mastermind')->group(function () {
    
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('sites', AdminSiteController::class)->names('admin.sites');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class)->names('admin.banners');

    Route::get('/review', [ App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::patch('/review/{review}/approve', [ App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('admin.reviews.approve');
    Route::delete('/review/{review}', [ App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    Route::get('featured-sites', [\App\Http\Controllers\Admin\SiteController::class, 'featured'])->name('admin.sites.featured');
    Route::patch('featured-sites/{site}/toggle', [\App\Http\Controllers\Admin\SiteController::class, 'toggleFeatured'])
    ->name('admin.sites.toggleFeatured');

    Route::get('/pending-sites', [App\Http\Controllers\Admin\SiteController::class, 'pending'])->name('admin.sites.pending');
    Route::patch('/pending-sites/{site}/approve', [App\Http\Controllers\Admin\SiteController::class, 'approve'])->name('admin.sites.approve');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::patch('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\UserController::class, 'toggleAdmin'])->name('admin.users.toggleAdmin');
    Route::patch('/users/{user}/toggle-ban', [\App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('admin.users.toggleBan');

    Route::get('/reports', [\App\Http\Controllers\Admin\SiteReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/reports/{report}/resolve', [\App\Http\Controllers\Admin\SiteReportController::class, 'resolve'])->name('admin.reports.resolve');

    Route::get('/banner-requests', [\App\Http\Controllers\Admin\BannerRequestController::class, 'index'])->name('banner-requests.index');
    Route::post('/banner-requests/{request}/approve', [\App\Http\Controllers\Admin\BannerRequestController::class, 'approve'])->name('banner-requests.approve');
    Route::post('/banner-requests/{request}/reject', [\App\Http\Controllers\Admin\BannerRequestController::class, 'reject'])->name('banner-requests.reject');

    Route::resource('devilcoin-packages', \App\Http\Controllers\Admin\DevilcoinPackageController::class)->except(['show']);

    Route::get('/claims', [App\Http\Controllers\Admin\SiteClaimAdminController::class, 'index'])->name('admin.claims.index');
    Route::get('/claims/{claim}', [\App\Http\Controllers\Admin\SiteClaimAdminController::class, 'show'])->name('admin.claims.show');
    Route::post('/claims/{claim}/approve', [App\Http\Controllers\Admin\SiteClaimAdminController::class, 'approve'])->name('admin.claims.approve');
    Route::post('/claims/{claim}/reject', [App\Http\Controllers\Admin\SiteClaimAdminController::class, 'reject'])->name('admin.claims.reject');
});
