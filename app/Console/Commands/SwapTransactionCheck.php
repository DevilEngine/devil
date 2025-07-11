<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StealthExService;
use Illuminate\Support\Facades\Log;

use CryptoQr\BitcoinQr;
use CryptoQr\CryptoQr;
use CryptoQr\Qr;

use App\Models\Swap\SwapTransaction;
use App\Models\User;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use Auth;

class SwapTransactionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:swap-transaction-check';
    protected $description = 'Verification des changements de statut des échanges.';

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
        $this->info('Début de la mise à jour des échanges...');

        $exchanges = $this->stealthExService->getExchangeList();

        foreach($exchanges as $exchange) {

            $swap = SwapTransaction::where('identifier', $exchange['id'])->first();

            if($swap){

                $now = strtotime(Carbon::now());
                $startNow = Carbon::now();
                $expiresAt = strtotime(Carbon::parse($swap->created_at)->addDays(30));
                $parseCarbon = Carbon::parse($swap->created_at)->addDays(30);
                $created_at = $swap->created_at;

                if($swap->status == "expired"){
                    continue;
                }
    
                if($now > $expiresAt){
                    $swap->delete();
                    continue;
                }

                $token_qr_code = $swap->qr_code;
                $fee_additional = $swap->fee_purcent;
                $token = $swap->token;
                $notifier = $swap->notifier;
                $user_id = $swap->user_id;
                
            }else{

                $crypt02 = openssl_random_pseudo_bytes(20);
                $token_qr_code = 'qrcode_'.bin2hex($crypt02).'.png';
                $qr = new Qr($exchange['deposit']['address']);
                $qr->getQrCode()->setSize(300);
                $pngData = $qr->writeFile(public_path('/img/qr_code/'.$token_qr_code));

                $notifier = 0;
                $fee_additional = 2.4;
                $token = 'Test swap';
                $user_id = null;

            }

            if(array_key_exists('err', $exchange)){
                $this->warn('Error:'.$exchange['err']['details']);
            }

            SwapTransaction::updateOrInsert(
                ['identifier' => $exchange['id']], // Clé unique (WHERE)
                [
                    'identifier' => $exchange['id'],
                    'token' => $token,
                    'fromCurrency' => $exchange['deposit']['symbol'],
                    'toCurrency' => $exchange['withdrawal']['symbol'],
                    'amount_to_receive' => $exchange['withdrawal']['amount'],
                    'amount_to_send' => $exchange['deposit']['amount'],
                    'expect_amount_to_receive' => $exchange['withdrawal']['expected_amount'],
                    'expect_amount_to_send' => $exchange['deposit']['expected_amount'],
                    'address_receive' => $exchange['withdrawal']['address'],
                    'address_send' => $exchange['deposit']['address'],
                    'fee_purcent' => $fee_additional,
                    'refund_address' => $exchange['refund_address'],
                    'tx_hash_receive' => $exchange['withdrawal']['tx_hash'],
                    'tx_hash_send' => $exchange['deposit']['tx_hash'],
                    'tx_explorer_url_receive' => $exchange['withdrawal']['tx_explorer_url'],
                    'tx_explorer_url_send' => $exchange['deposit']['tx_explorer_url'],
                    'status' => $exchange['status'],
                    'qr_code' => $token_qr_code,
                    'notifier' => $notifier,
                    'created_at' => Carbon::parse($exchange['created_at'])->setTimezone('UTC')->format('Y-m-d H:i:s'),
                ]
            );

            $swap_update = SwapTransaction::where('identifier', $exchange['id'])->first();

            if($swap_update){
                if($swap_update->status == "waiting"){
                    $now = strtotime(Carbon::now());
                    $expiresAt = strtotime(Carbon::parse($swap_update->created_at)->addDays(1));
        
                    if($now > $expiresAt){
                        $swap_update->update(['status' => 'expired']);
                        continue;
                    }

                    $expires20Minutes = strtotime(Carbon::parse($swap_update->created_at)->addMinutes(20));
                    if($swap_update->fix_reversed == 1){
                        if($now > $expires20Minutes){
                            $swap_update->update(['status' => 'expired']);
                            continue;
                        }
                    }
                }
            }

            if($swap_update->status == "finished"){
                if($swap_update->notifier == 0){
                    //$admins = User::whereHas('admin')->get(); 
                    //foreach($admins as $item){

                    //    $notif_user = new HistoryAdmin();
                    //    $notif_user->user_id = $item->id;
                    //    $notif_user->message = 'New Swap ID '.e($exchange['id']).' is complete !';
                    //    $notif_user->type = 'swap';
                    //    $notif_user->save();
                    //}

                    SwapTransaction::where('identifier', $exchange['id'])->update(['notifier' => 1]);

                    

                }

            }

        }

        Log::info(count($exchanges). ' swaps updated or added with success !');
        $this->info(count($exchanges) . ' échanges mis à jour avec succès.');
    }
}
