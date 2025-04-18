<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

use Spatie\Tags\HasTags;

class Site extends Model
{
    use HasTags;

    protected $casts = [
        'featured_home_until' => 'datetime',
        'feature_category_until' => 'datetime',
        'feature_tag_until' => 'datetime',
    ];

    protected $fillable = [
        'name', 'slug','url','mirror_1', 'user_id','mirror_2','description', 'logo_path', 'category_id', 'status','featured','featured_home','featured_category','featured_tag',
        'no_kyc','has_onion','decentralized','smart_contract','featured_home_until','feature_category_until','feature_tag_until'
    ];

    public function scopeDarknet($query)
    {
        return $query->where(function ($q) {
            $q->where('url', 'like', '%.onion%')
            ->orWhere('mirror_1', 'like', '%.onion%')
            ->orWhere('mirror_2', 'like', '%.onion%');
        });
    }

    // Un site appartient à une catégorie
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(SiteReview::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function claims() {
        return $this->hasMany(SiteClaim::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function isCurrentlyFeatured(): bool
    {

        return (
            ($this->featured_home && $this->featured_home_until?->isFuture()) ||
            ($this->featured_category && $this->feature_category_until?->isFuture()) ||
            ($this->featured_tag && $this->feature_tag_until?->isFuture())
        );
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function trustVotes()
    {
        return $this->hasMany(\App\Models\SiteTrustVote::class);
    }

    public function scopeFeaturedHome($query)
    {
        return $query
            ->where('featured_home', true)
            ->where(function ($q) {
                $q->whereNull('featured_home_until')
                ->orWhere('featured_home_until', '>', now());
            });
    }

    public function trustScore(): float
    {
        $votes = $this->trustVotes()->with('user')->get();
    
        if ($votes->isEmpty()) return 0;
    
        $totalWeight = 0;
        $trustedWeight = 0;
    
        foreach ($votes as $vote) {
            $weight = $vote->user && $vote->user->isTrustedUser() ? 2 : 1;
            $totalWeight += $weight;
    
            if ($vote->trusted) {
                $trustedWeight += $weight;
            }
        }
    
        return round(($trustedWeight / $totalWeight) * 100, 1);
    }

    public function trustScoreCached(): float
    {
        if (! $this->relationLoaded('trustVotes')) return 0;

        $total = $this->trustVotes->count();
        if ($total === 0) return 0;

        $trusted = $this->trustVotes->where('trusted', true)->count();
        return round(($trusted / $total) * 100, 1);
    }

    protected static function booted()
    {
        static::creating(function ($site) {
            $site->slug = Str::slug($site->name);
        });

        static::updating(function ($site) {
            $site->slug = Str::slug($site->name);
        });
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(SiteReport::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'site_follows')->withTimestamps();
    }

    public function logoOrAvatarUrl(): string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) .
            '&background=198754&color=fff&bold=true';
    }
}
