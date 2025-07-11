<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DevilcoinPackage;
use App\Models\DevilcoinPurchase;
use Illuminate\Support\Facades\Http;

use Auth;

class DevilcoinPurchaseController extends Controller
{
    public function index()
    {
        // Packs configurés ici ou en config
        $packs = DevilcoinPackage::where('active', true)->orderBy('amount')->get();

        return view('user.devilcoin.buy', compact('packs'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:devilcoin_packages,id',
        ]);

        $user = auth()->user();
        $package = DevilcoinPackage::where('active', true)->findOrFail($request->package_id);

        // Créer la transaction en BDD
        $purchase = DevilcoinPurchase::create([
            'user_id' => $user->id,
            'devilcoin_package_id' => $package->id,
            'amount' => $package->amount,
            'price' => $package->usd_price,
            'status' => 'pending',
        ]);

        // Appel à NOWPayments.io
        $response = Http::withHeaders([
            'x-api-key' => config('services.nowpayments.api_key'), // à mettre dans .env
        ])->post('https://api.nowpayments.io/v1/invoice', [
            'price_amount'       => $purchase->price,
            'price_currency'     => 'USD',
            'order_id'           => $purchase->id,
            'order_description'  => "Purchase of {$package->amount} DEVC",
            'ipn_callback_url'   => route('nowpayments.webhook'), // prochaine étape
            'success_url'        => route('devilcoin.usage.all'), // ou autre
            'cancel_url'         => route('devilcoins.buy'),
        ]);

        if ($response->failed()) {
            return back()->with('error', 'NOWPayments request failed.');
        }

        $data = $response->json();

        // Met à jour l’enregistrement avec le lien de paiement
        $purchase->update([
            'payment_id' => $data['token_id'],
            'invoice_url' => $data['invoice_url'],
        ]);

        return redirect($data['invoice_url']);
    }

    public function paymentPage($purchase)
    {
        $purchase = DevilcoinPurchase::where('id', $purchase)->where('user_id', Auth::id())->firstOrFail();
    
        return view('user.devilcoin.payment', compact('purchase'));
    }


    public function history()
    {
        $purchases = auth()->user()
            ->devilcoinPurchases()
            ->latest()
            ->get();

        return view('user.devilcoin.history', compact('purchases'));
    }

}

