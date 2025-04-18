<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevilcoinUsage extends Model
{
    protected $fillable = ['user_id', 'description', 'amount'];
}
