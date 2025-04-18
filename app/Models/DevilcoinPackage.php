<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevilcoinPackage extends Model
{
    protected $fillable = ['amount', 'usd_price', 'active'];
}
