<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'image_path', 'url', 'position', 'active','site_id', 'user_id', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                    });
    }

    public function site()
    {
        return $this->belongsTo(\App\Models\Site::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}