<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerRequest extends Model
{
    protected $fillable = ['user_id', 'site_id', 'title', 'image','position','status','admin_note','external_url'];

    public function site()
    {
        return $this->belongsTo(\App\Models\Site::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
