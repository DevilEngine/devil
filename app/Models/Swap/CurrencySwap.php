<?php

namespace App\Models\Swap;

use Illuminate\Database\Eloquent\Model;

class CurrencySwap extends Model
{
    protected $table = 'currency_swaps';

    protected $fillable = [
        'symbol',
        'network',
        'legacy_symbol',
        'name',
    ];
}
