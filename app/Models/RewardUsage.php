<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardUsage extends Model
{
    protected $fillable = ['user_id', 'site_id', 'reward_key', 'label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
