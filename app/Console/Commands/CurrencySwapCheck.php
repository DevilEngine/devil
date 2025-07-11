<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Swap\CurrencySwap;

use App\Services\StealthExService;

class CurrencySwapCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:currency-swap';

    protected $description = 'Command description';

    protected StealthExService $stealthExService;

    public function __construct(StealthExService $stealthExService)
    {
        parent::__construct();
        $this->stealthExService = $stealthExService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $currencies_mainnet = $this->stealthExService->getAvailableCurrencies('mainnet');
        $usdt = $this->stealthExService->getCurrencyDetails('usdt','eth');
        $usdc = $this->stealthExService->getCurrencyDetails('usdc','eth');
        $busd = $this->stealthExService->getCurrencyDetails('busd','eth');
        $usdtrc20 = $this->stealthExService->getCurrencyDetails('usdt','trx');

        foreach($currencies_mainnet as $item){

            $currency_swap = CurrencySwap::where('name', $item['name'])->first();

            if($currency_swap){
                continue;
            }

            CurrencySwap::create([
                'symbol' => $item['symbol'],
                'network' => $item['network'],
                'legacy_symbol' => $item['legacy_symbol'],
                'name' => $item['name'],
            ]);
        }

        $this->info(count($currencies_mainnet).' currencies added');
    }
}
