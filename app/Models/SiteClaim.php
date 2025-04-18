<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Site;

class SiteClaim extends Model
{

    protected $fillable = ['user_id', 'site_id','reason','status','admin_note','proof_type','proof_details','message'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function site() {
        return $this->belongsTo(Site::class);
    }
}
