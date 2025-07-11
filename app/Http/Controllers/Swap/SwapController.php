<?php

namespace App\Http\Controllers\Swap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\StealthExService;
use App\Services\CaptchaService;

use App\Models\Swap\SwapTransaction;
use App\Models\Swap\CurrencySwap;

use CryptoQr\BitcoinQr;
use CryptoQr\CryptoQr;
use CryptoQr\Qr;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use Auth;
use Log;

class SwapController extends Controller
{
    protected $stealthExService;

    public function __construct(StealthExService $stealthExService)
    {
        $this->stealthExService = $stealthExService;
    }

    public function start(Request $request)
    {   
        if($request->input('action') == "switch"){

            $newto = $request->input('fromCurrency');
            $newfrom = $request->input('toCurrency');

            session()->put('startfromCurrency', $newfrom);
            session()->put('starttoCurrency', $newto);

            return redirect(route('swap.start'));
        }

        if($request->input('captcha_x') && $request->input('captcha_y')){

            $captcha = app(CaptchaService::class);

            if ($captcha->isCaptchaBlocked()) {
                return redirect()->back()->withErrors([
                    'captcha' => 'Too much try, try again later.'
                ]);
            }
            
            if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
                $captcha->registerCaptchaAttempt();
                return redirect()->back()->withErrors([
                    'captcha' => 'CAPTCHA incorrect.'
                ]);
            }
            
            $captcha->resetCaptchaAttempts();

            session()->put('startfromCurrency',$request->input('fromCurrency'));
            session()->put('starttoCurrency', $request->input('toCurrency'));
                    
            $validated = $request->validate([
                'fromCurrency' => 'required|string',
                'toCurrency' => 'required|string',
                'amount' => 'required|numeric|min:0.0001',
                
            ]);

            $currency_from = CurrencySwap::where('legacy_symbol', $request->input('fromCurrency'))->first();
            $currency_to = CurrencySwap::where('legacy_symbol', $request->input('toCurrency'))->first();

            $estimateAmount = $this->stealthExService->getEstimatedExchangeAmount(
                $currency_from->symbol,
                $currency_to->symbol,
                $validated['amount'],
                2.6,
                $currency_from->network,
                $currency_to->network,
                'direct',
                'floating'
            );

            //dd($estimateAmount);

            // Si aucune route d’échange, on réessaie sans frais
            if (
                array_key_exists('err', $estimateAmount)
                && (
                    $estimateAmount['err']['details'] === "No exchange route available"
                    || $estimateAmount['err']['details'] === "Market is currently unavailable"
                )
            ) {
                $estimateAmount = $this->stealthExService->getEstimatedExchangeAmount(
                    $currency_from->symbol,
                    $currency_to->symbol,
                    $validated['amount'],
                    0,
                    $currency_from->network,
                    $currency_to->network,
                    'direct',
                    'floating'
                );
            }

            // Si toujours une erreur, on retourne avec le message
            if (array_key_exists('err', $estimateAmount)) {
                return redirect()->back()->with('error', $estimateAmount['err']['details']);
            }

            session()->put('fromCurrency', $request->input('fromCurrency'));
            session()->put('fromCurrencyNetwork', '');
            session()->put('toCurrency', $request->input('toCurrency'));
            session()->put('toCurrencyNetwork', '');
            session()->put('amount', $request->input('amount'));
            session()->put('estimate', $estimateAmount['estimated_amount']);
            if(Auth::check()){
                session()->put('method', $request->input('method'));
            }

            return redirect(route('swap.confirming'));
        }
    }


    public function showExchangeForm(Request $request)
    {
        if(!session()->has('startfromCurrency')){
            session()->put('startfromCurrency', 'btc');
            session()->put('starttoCurrency', 'eth');
        }

        $captcha = app(CaptchaService::class)->generateCaptcha();

        $currencies = CurrencySwap::get();

        return view('swap.start', compact('currencies','captcha'));
    }


    public function showConfirmingForm()
    {
        if(!session()->get('fromCurrency')){
            return redirect(route('swap.start'))->with('error','You need to start a swap before to access this page !');
        }

        return view('swap.confirm');
    }


    public function createExchange(Request $request)
    {

        $validated = $request->validate([
            'address' => 'required|string',
            'refundAddress' => 'required|string',
        ]);

        $symbol_from = CurrencySwap::where('legacy_symbol', session()->get('startfromCurrency'))->first();
        $symbol_to = CurrencySwap::where('legacy_symbol', session()->get('starttoCurrency'))->first();

        $network_from = CurrencySwap::where('legacy_symbol', session()->get('fromCurrency'))->first();
        $network_to = CurrencySwap::where('legacy_symbol', session()->get('toCurrency'))->first();

        $fee_additional = 2.6;

        $response = $this->stealthExService->createExchange(
            session()->get('amount'),
            $symbol_from->symbol,
            $symbol_to->symbol,
            $validated['address'],
            $validated['refundAddress'],
            $fee_additional,
            $network_from->network,
            $network_to->network
        );
        
        // Si la première tentative échoue à cause d'une route indisponible, on tente sans frais
        if (
            array_key_exists('err', $response)
            && (
                $response['err']['details'] === "No exchange route available"
                || $response['err']['details'] === "Market is currently unavailable"
            )
        ) {
            
            $fee_additional = 0;
            
            $response = $this->stealthExService->createExchange(
                session()->get('amount'),
                $symbol_from->symbol,
                $symbol_to->symbol,
                $validated['address'],
                $validated['refundAddress'],
                $fee_additional,
                $network_from->network,
                $network_to->network
            );
        }
        
        // Si toujours une erreur, on retourne en arrière avec le message
        if (array_key_exists('err', $response)) {
            return redirect()->back()->with('error', $response['err']['details']);
        }

        $crypt02 = openssl_random_pseudo_bytes(20);
        $token_qr_code = 'qrcode_'.bin2hex($crypt02).'.png';

        $qr = new Qr($response['deposit']['address']);
        $qr->getQrCode()->setSize(300);
        $pngData = $qr->writeFile($_SERVER['DOCUMENT_ROOT'].'/img/qr_code/'.$token_qr_code);

        $token = Uuid::uuid4()->toString();

        SwapTransaction::updateOrInsert(
            ['identifier' => $response['id']], // Clé unique (WHERE)
            [
                'identifier' => $response['id'],
                'token' => $token,
                'fromCurrency' => $response['deposit']['symbol'],
                'toCurrency' => $response['withdrawal']['symbol'],
                'amount_to_receive' => $response['withdrawal']['amount'],
                'amount_to_send' => $response['deposit']['amount'],
                'expect_amount_to_receive' => $response['withdrawal']['expected_amount'],
                'expect_amount_to_send' => $response['deposit']['expected_amount'],
                'fee_purcent' => $fee_additional,
                'address_receive' => $response['withdrawal']['address'],
                'address_send' => $response['deposit']['address'],
                'refund_address' => $response['refund_address'],
                'tx_hash_receive' => $response['withdrawal']['tx_hash'],
                'tx_hash_send' => $response['deposit']['tx_hash'],
                'tx_explorer_url_receive' => $response['withdrawal']['tx_explorer_url'],
                'tx_explorer_url_send' => $response['deposit']['tx_explorer_url'],
                'status' => $response['status'],
                'qr_code' => $token_qr_code,
                'notifier' => 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect(route('swap.show', $token))->with('success','Swap started with success !');
    }

    public function getExchange($responseId)
    {

        $swap_verif = SwapTransaction::where('token', $responseId)->firstOrFail();

        if(!session()->get('fromCurrency')){
            return redirect(route('swap.start'))->with('error','You need to start a swap before to access this page !');
        }

        $swap_transaction = SwapTransaction::where('token', $responseId)->whereNotIn('status', ['finished', 'expired', 'failed', 'refunded'])->first();
        $swap_finish = SwapTransaction::where('token', $responseId)->whereNotIn('status', ['waiting', 'confirming', 'exchanging', 'sending', 'verifying'])->first();

        if($swap_finish){
            return redirect(route('swap.final', $responseId));
        }

        return view('swap.swap')->with(compact('swap_transaction'));
    }


    public function getExchangeBySearch(Request $request)
    {

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->back()->withErrors([
                'captcha' => 'Too much try, try again later.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->back()->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }

        $captcha->resetCaptchaAttempts();

        $request->validate([
            'identifier' => 'required|string|uuid',
        ]);

        $identifier = e($request->input('identifier'));

        $swap_transaction = SwapTransaction::where('token', $identifier)->first();

        if($swap_transaction){
            
                session()->put('fromCurrency', $swap_transaction->fromCurrency);
                session()->put('toCurrency', $swap_transaction->toCurrency);
                session()->put('amount', $swap_transaction->amount_to_send);
                session()->put('estimate', $swap_transaction->expect_amount_to_send);

                return redirect(route('swap.show', $swap_transaction->token));

        }else{
            return redirect(route('swap.start'))->with('error','This swap id dont exist !');
        }

    }


    public function showFinished($responseId)
    {

        if(!session()->get('fromCurrency')){
            return redirect(route('swap.start'))->with('error','You need to start a swap before to access this page !');
        }

        $swap_transaction = SwapTransaction::where('token', $responseId)->whereNotIn('status', ['waiting', 'confirming', 'exchanging', 'sending', 'verifying'])->first();

        if(!$swap_transaction){
            return redirect(route('swap.start'))->with('error','Swap not finished or not exist!');
        }

        $exchange = $this->stealthExService->getExchange($swap_transaction->identifier);

        return view('swap.finish')->with(compact('swap_transaction','exchange'));
    }

}
