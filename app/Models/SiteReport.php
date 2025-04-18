<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteReport extends Model
{
    protected $fillable = ['site_id', 'user_id', 'reason', 'message', 'resolved'];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
