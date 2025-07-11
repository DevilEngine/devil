<?php

namespace App\Models\Swap;

use Illuminate\Database\Eloquent\Model;
use App\Services\CryptoRateService;

class SwapTransaction extends Model
{
    protected $table = 'swap_transactions';

    protected $fillable = [
        'fromCurrency',
        'toCurrency',
        'amount_to_receive',
        'amount_to_send',
        'expect_amount_to_receive',
        'expect_amount_to_send',
        'fee_purcent',
        'address_receive',
        'address_send',
        'refund_address',
        'tx_hash_receive',
        'tx_hash_send',
        'tx_explorer_url_receive',
        'tx_explorer_url_send',
        'status',
        'qr_code',
        'user_id',
        'notifier',
        'tombola_recipient',
        'fix_reversed',
        'transaction_vouch',
    ];

        /**
     * Calcule le montant de la commission en USD avec Binance
     */
    public function getCommissionInUSDAttribute()
    {
        $cryptoRateService = new CryptoRateService();
        $rate = $cryptoRateService->getRate($this->toCurrency);

        $commission = (float) $this->fee_purcent / 100 * (float) $this->amount_to_receive;
        $commissionUSD = $commission * $rate;

        \Log::info("Commission calculée : fee_com={$this->fee_purcent}, amount_to_receive={$this->amount_to_receive}, rate={$rate}, commissionUSD={$commissionUSD}");

        return $commissionUSD;
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function tombola(){
        return $this->belongsTo('App\Models\Swap\Tombola','identifier','swap_identifier');
    }

    public static function maskMiddle($str) {
        $length = strlen($str);
        
        if ($length < 10) {
            return "La chaîne est trop courte pour l'opération.";
        }
    
        $first5 = substr($str, 0, 5);  // Extraire les 5 premiers caractères
        $last5 = substr($str, -5);    // Extraire les 5 derniers caractères
    
        return $first5 . '***************' . $last5;  // Concaténer avec ***
    }
}
