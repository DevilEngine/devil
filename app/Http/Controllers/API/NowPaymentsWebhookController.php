<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DevilcoinPurchase;
use Illuminate\Support\Facades\Log;

class NowPaymentsWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Optionnel : log pour debug
        Log::info('NOWPayments Webhook Received', $request->all());

        $data = $request->all();

        // Vérifie l'identifiant local
        $purchase = DevilcoinPurchase::find($data['order_id'] ?? null);

        if (!$purchase || $purchase->status !== 'pending') {
            return response()->json(['message' => 'Invalid or already processed.'], 200);
        }

        if ($data['payment_status'] === 'confirmed') {
            $purchase->update([
                'status'        => 'confirmed',
                'pay_currency'  => $data['pay_currency'] ?? null,
                'tx_id'         => $data['payment_id'] ?? null,
                'confirmed_at'  => now(),
            ]);

            // Créditer les DevilCoins (décrémenter coins_spent)
            $user = $purchase->user;
            $user->decrement('coins_spent', $purchase->amount);
        } elseif ($data['payment_status'] === 'expired' || $data['payment_status'] === 'failed') {
            $purchase->update([
                'status' => $data['payment_status'],
            ]);
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
