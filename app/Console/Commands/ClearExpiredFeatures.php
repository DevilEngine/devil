<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Site;
use Carbon\Carbon;

class ClearExpiredFeatures extends Command
{
    protected $signature = 'sites:clean-expired-features';
    protected $description = 'Deactivate expired featured sites (homepage, category, tag)';

    public function handle()
    {
        $now = Carbon::now();

        $expired = Site::query()
            ->where(function ($q) use ($now) {
                $q->where('featured_home', true)->whereNotNull('featured_home_until')->where('featured_home_until', '<', $now)
                  ->orWhere('featured_category', true)->whereNotNull('feature_category_until')->where('feature_category_until', '<', $now)
                  ->orWhere('featured_tag', true)->whereNotNull('feature_tag_until')->where('feature_tag_until', '<', $now);
            })
            ->get();

        $expired->each(function ($site) {
            if ($site->featured_home && $site->featured_home_until && $site->featured_home_until->isPast()) {
                $site->featured_home = false;
                $site->featured_home_until = null;
            }

            if ($site->featured_category && $site->feature_category_until && $site->feature_category_until->isPast()) {
                $site->featured_category = false;
                $site->feature_category_until = null;
            }

            if ($site->featured_tag && $site->feature_tag_until && $site->feature_tag_until->isPast()) {
                $site->featured_tag = false;
                $site->feature_tag_until = null;
            }

            $site->save();
        });

        $this->info("✅ {$expired->count()} featured site(s) cleaned.");
    }
}

