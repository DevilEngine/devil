<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevilcoinPurchase extends Model
{
    protected $fillable = 
    [
        'devilcoin_package_id', 
        'status', 
        'payment_id',
        'user_id',
        'invoice_url',
        'pay_currency',
        'tx_id',
        'price',
        'amount',
        'confirmed_at',
        'user_id'
    ];
}
