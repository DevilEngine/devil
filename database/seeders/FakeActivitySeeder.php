<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Site;
use App\Models\SiteReview;
use App\Models\SiteTrustVote;
use Illuminate\Database\Seeder;

class FakeActivitySeeder extends Seeder
{
    public function run()
    {
        $users = User::inRandomOrder()->take(10)->get(); // 10 users
        $sites = Site::inRandomOrder()->take(20)->get(); // 20 sites

        foreach ($sites as $site) {
            foreach ($users->shuffle()->take(rand(1, 5)) as $user) {
                // Trust vote
                SiteTrustVote::firstOrCreate([
                    'user_id' => $user->id,
                    'site_id' => $site->id,
                ], [
                    'trusted' => rand(0, 1),
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);

                // Review
                if (rand(0, 1)) {
                    SiteReview::create([
                        'user_id' => $user->id,
                        'site_id' => $site->id,
                        'rating' => rand(3, 5),
                        'approved' => true,
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]);
                }
            }
        }
    }
}
