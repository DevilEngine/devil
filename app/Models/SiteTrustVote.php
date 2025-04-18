<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteTrustVote extends Model
{
    protected $fillable = ['user_id', 'site_id', 'trusted'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function getWeightAttribute(): int
    {
        if ($this->user && $this->user->isTrustedUser()) {
            return 2;
        }

        return 1;
    }
}
