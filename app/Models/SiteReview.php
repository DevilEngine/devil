<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteReview extends Model
{
    protected $fillable = ['site_id', 'user_id', 'rating', 'comment','approved'];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

}
