<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DevilcoinWithdrawal;
use Illuminate\Http\Request;

use Auth;

class DevilcoinWithdrawController extends Controller
{
    public function index()
    {
        $withdrawals = DevilcoinWithdrawal::latest()->paginate(20);
        return view('admin.withdrawal.index', compact('withdrawals'));
    }

    public function approve(Request $request, $id)
    {
        $withdrawal = DevilcoinWithdrawal::where('id', $id)->where('user_id', Auth::id())->first();

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }
    
        $request->validate([
            'tx_id' => 'nullable|string|max:255',
        ]);
    
        $withdrawal->update([
            'status'       => 'approved',
            'admin_note'   => $request->tx_id,
            'processed_at' => now(),
        ]);
    
        return back()->with('success', 'Withdrawal approved and transaction ID saved.');
    }

    public function reject(Request $request, $id)
    {

        $withdrawal = DevilcoinWithdrawal::where('id', $id)->where('user_id', Auth::id())->first();

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }

        // On redonne les coins à l’utilisateur
        $withdrawal->user->decrement('coins_spent', $withdrawal->amount);

        $withdrawal->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'processed_at' => now(),
        ]);

        return back()->with('warning', 'Withdrawal rejected and coins refunded.');
    }
}
