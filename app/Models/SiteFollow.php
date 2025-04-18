<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteFollow extends Model
{
    protected $fillable = ['user_id', 'site_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

