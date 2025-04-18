<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'password', 'mnemonic_key','avatar_path','slug','bio','reputation','is_admin','banned','free_feature_home','free_banner_slots','coins_spent'
    ];

    protected $hidden = [
        'password', 'mnemonic_key',
    ];

    public function reviews()
    {
        return $this->hasMany(\App\Models\SiteReview::class);
    }

    public function purchases()
    {
        return $this->hasMany(\App\Models\DevilCoinPurchase::class, 'user_id', 'id');
    }

    public function sites()
    {
        return $this->hasMany(\App\Models\Site::class);
    }

    public function trustVotes()
    {
        return $this->hasMany(\App\Models\SiteTrustVote::class);
    }

    public function isContributor(): bool
    {
        return $this->sites()->where('status', 'active')->exists();
    }

    public function earnedDevilCoins(): int
    {

        return $this->reviews()->where('approved', true)->count() * 5
             + $this->sites()->where('status', 'active')->count() * 10
             + $this->trustVotes()->count()
             + $this->purchases()->where('status','confirmed')->sum('amount');
    }
    
    public function availableDevilCoins(): int
    {
        return max(0, $this->earnedDevilCoins() - $this->coins_spent);
    }

    public function devilcoinUsages()
    {
        return $this->hasMany(\App\Models\DevilcoinUsage::class);
    }
    
    public function devilCoins(): string
    {
        return $this->availableDevilCoins() . ' DEVC';
    }

    public function devilcoinPurchases()
    {
        return $this->hasMany(\App\Models\DevilcoinPurchase::class);
    }

    public function bannerRequests()
    {
        return $this->hasMany(\App\Models\BannerRequest::class);
    }

    public function reports()
    {
        return $this->hasMany(SiteReport::class);
    }

    public function rewardUsages()
    {
        return $this->hasMany(\App\Models\RewardUsage::class);
    }

    public function hasExtendedSubmissionLimit(): bool
    {
        return $this->reputation >= 50;
    }

    public function followedSites()
    {
        return $this->belongsToMany(Site::class, 'site_follows')->withTimestamps();
    }

    public function banners()
    {
        return $this->hasMany(\App\Models\Banner::class);
    }

    public function isTrustedUser(): bool
    {
        return $this->reputation >= 500;
    }

    public function devilRank(): array
    {
        $coins = $this->reputation;
    
        return match (true) {
            $coins >= 5000 => ['👑 Devil King', 'badge-king'],
            $coins >= 3000 => ['🕷 Abyss Warden', 'badge-animated-dark'],
            $coins >= 2000 => ['🐉 Archfiend', 'badge-animated-red'],
            $coins >= 1000 => ['💎 Abyss Lord', 'badge-purple'],
            $coins >= 500  => ['🛡 Trusted Soul', 'bg-info'],
            $coins >= 200  => ['👑 Lord of the Abyss', 'bg-danger'],
            $coins >= 100  => ['👹 Demonic Gold', 'bg-warning'],
            $coins >= 50   => ['💀 Hellfire Silver', 'bg-secondary'],
            $coins >= 20   => ['🔥 Infernal Bronze', 'badge-bronze'],
            default        => ['🪙 New Soul', 'bg-secondary'],
        };
    }    

    public function unlockedRewards(): array
    {
        $rewards = [];

        if ($this->reputation - $this->coins_spent >= 10) {
            $rewards[] = ['label' => '🏅 Contributor Badge', 'unlocked' => true];
        }

        if ($this->reputation - $this->coins_spent >= 50) {
            $rewards[] = ['label' => '🧾 Extended Submission Limit', 'unlocked' => true];
        }

        if ($this->reputation - $this->coins_spent >= 100) {
            $rewards[] = ['label' => '🔥 Free Homepage Feature (1x)', 'unlocked' => true];
        }

        if ($this->reputation - $this->coins_spent >= 150) {
            $rewards[] = ['label' => '📊 Access to Site Stats', 'unlocked' => true];
        }

        if ($this->reputation - $this->coins_spent >= 200) {
            $rewards[] = ['label' => '👹 Demonic Elite Badge', 'unlocked' => true];
        }

        if ($this->reputation - $this->coins_spent >= 300) {
            $rewards[] = ['label' => '🎁 Free Banner Slot (1x)', 'unlocked' => true];
        }

        if ($this->reputation - $this->coins_spent >= 500) {
            $rewards[] = ['label' => '🛡️ Trusted User Role', 'unlocked' => true];
        }

        return $rewards;
    }

    public function nextRewards(): array
    {
        $steps = [
            10 => '🏅 Contributor Badge',
            50 => '🧾 Extended Submission Limit',
            100 => '🔥 Free Homepage Feature (1x)',
            150 => '📊 Access to Site Stats',
            200 => '👹 Demonic Elite Badge',
            300 => '🎁 Free Banner Slot (1x)',
            500 => '🛡️ Trusted User Role',
        ];
    
        $available = max(0, $this->reputation - $this->coins_spent);
    
        return collect($steps)
            ->filter(fn($label, $threshold) => $available < $threshold)
            ->map(fn($label, $threshold) => ['label' => $label, 'at' => $threshold])
            ->values()
            ->toArray();
    }


    public function devilProgress(): array
    {
        $devilCoins = $this->reputation;

        $steps = [
            ['label' => '🪙 New Soul',         'min' => 0,     'max' => 20],
            ['label' => '🔥 Infernal Bronze',  'min' => 20,    'max' => 50],
            ['label' => '💀 Hellfire Silver',  'min' => 50,    'max' => 100],
            ['label' => '👹 Demonic Gold',     'min' => 100,   'max' => 200],
            ['label' => '👑 Lord of the Abyss','min' => 200,   'max' => 500],
            ['label' => '🛡 Trusted Soul',     'min' => 500,   'max' => 1000],
            ['label' => '💎 Abyss Lord',       'min' => 1000,  'max' => 2000],
            ['label' => '🐉 Archfiend',        'min' => 2000,  'max' => 3000],
            ['label' => '🕷 Abyss Warden',     'min' => 3000,  'max' => 5000],
            ['label' => '👑 Devil King',       'min' => 5000,  'max' => null],
        ];

        foreach ($steps as $i => $step) {
            if ($step['max'] === null || $devilCoins < $step['max']) {
                $next = $step['max'];
                $current = $devilCoins - $step['min'];
                $range = ($next - $step['min']) ?? 1;
                $percent = min(100, round(($current / $range) * 100));
                return [
                    'current_rank' => $step['label'],
                    'next_rank'    => $steps[$i + 1]['label'] ?? null,
                    'coins_needed' => $next ? ($next - $devilCoins) : 0,
                    'progress'     => $percent,
                    'current'      => $devilCoins,
                    'next_at'      => $next,
                ];
            }
        }

        return [
            'current_rank' => '👑 Max Level',
            'next_rank'    => null,
            'coins_needed' => 0,
            'progress'     => 100,
            'current'      => $devilCoins,
            'next_at'      => null,
        ];
    }

    public function reputation(): int
    {
        $earned = $this->reviews()->where('approved', true)->count() * 5
                + $this->sites()->where('status', 'active')->count() * 10
                + $this->trustVotes()->count()
                + $this->purchases()->where('status','confirmed')->sum('amount');

        return max(0, $earned - $this->coins_spent); // ⚠️ Ne jamais être < 0
    }

    public function updateReputation(): void
    {
        $old = $this->reputation;
    
        $sitesCount   = $this->sites()->where('status', 'active')->count();
        $reviewsCount = $this->reviews()->where('approved', true)->count();
        $votesCount   = $this->trustVotes()->count();
        $purchases    = $this->purchases()->where('status','confirmed')->sum('amount');
    
        $score = ($sitesCount * 10) + ($reviewsCount * 5) + ($votesCount * 1) + ($purchases * 1);
        $this->reputation = $score;
    
        // Free homepage feature
        if ($old < 100 && $score >= 100) {
            $this->free_feature_home += 1;
        }
    
        // Free banner slot
        if ($old < 300 && $score >= 300) {
            $this->free_banner_slots += 1;
        }
    
        $this->save();
    }
      


    public static function generateMnemonic()
    {
        $words = [
            'apple', 'banana', 'cherry', 'dragon', 'elephant', 'forest', 'galaxy', 'horizon', 'island', 'jungle', 'kangaroo', 'lightning',
            'mountain', 'nebula', 'ocean', 'penguin', 'quantum', 'rainbow', 'sunset', 'tiger', 'universe', 'volcano', 'waterfall', 'xenon',
            'yacht', 'zeppelin', 'hurricane', 'desert', 'iceberg', 'network', 'cloud', 'storm', 'wind', 'fire', 'snowflake', 'thunder',
            'diamond', 'emerald', 'sapphire', 'ruby', 'phoenix', 'warrior', 'guardian', 'knight', 'wizard', 'samurai', 'pirate', 'viking',
            'ninja', 'cyber', 'cosmos', 'gravity', 'planet', 'asteroid', 'galactic', 'nebula', 'supernova', 'blackhole', 'comet', 'spaceship',
            'engine', 'cipher', 'riddle', 'voyage', 'pulse', 'energy', 'horizon', 'mirage', 'illusion', 'mystic', 'oracle', 'prophet',
            'phantom', 'enigma', 'shadow', 'eclipse', 'whirlwind', 'blizzard', 'tempest', 'cyclone', 'tornado', 'monsoon', 'avalanche',
            'crystal', 'alchemy', 'runestone', 'pyramid', 'fortress', 'castle', 'dungeon', 'labyrinth', 'maze', 'secret', 'hidden', 'unknown'
        ];
    
        shuffle($words);
        return implode(' ', array_slice($words, 0, 10));
    }

    public function favorites()
    {
        return $this->belongsToMany(Site::class, 'favorites')->withTimestamps();
    }

    public function hasFavorited(Site $site): bool
    {
        return $this->favorites()->where('site_id', $site->id)->exists();
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = static::generateSlug($user->username);
            }
        });

        static::updating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = static::generateSlug($user->username);
            }
        });
    }

    public static function generateSlug($name)
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function avatarUrl(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }
    
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->username) .
            '&background=198754&color=fff&bold=true';
    }
}
