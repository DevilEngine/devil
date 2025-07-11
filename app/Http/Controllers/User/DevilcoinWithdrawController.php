<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DevilcoinWithdrawal;
use Illuminate\Support\Facades\Auth;
use App\Services\CaptchaService;

class DevilcoinWithdrawController extends Controller
{
    // 🔍 Liste des retraits pour l'utilisateur
    public function index()
    {
        $withdrawals = DevilcoinWithdrawal::where('user_id', Auth::id())
            ->latest()
            ->get();

        // 💱 Taux de conversion : 0.001 XMR pour 100 DevilCoins
        $xmrRatePerDevilcoin = 0.01 / 100;

        return view('user.withdrawal.index', compact('withdrawals','xmrRatePerDevilcoin'));
    }

    // 📤 Formulaire de retrait
    public function create()
    {
        $captcha = app(CaptchaService::class)->generateCaptcha();
        return view('user.withdrawal.create')->with(compact('captcha'));
    }

    // ✅ Soumission du formulaire
    public function store(Request $request)
    {

        $request_withdrawal = DevilcoinWithdrawal::where('status','pending')->where('user_id', Auth::id())->first();

        if($request_withdrawal){
            return redirect(route('user.withdrawals.index'))->with('error','You cant send a new withdrawal request with withdrawal already in progress !');
        }

        $request->validate([
            'amount'         => 'required|integer|min:1000',
            'xmr_address' => 'required|regex:/^[48][0-9A-Za-z]{94,106}$/|min:30|max:120',
        ]);

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

        $user = Auth::user();

        if ($user->availableDevilCoins() < $request->amount) {
            return back()->with('error', 'Not enough DevilCoins available.');
        }

        // On ajoute à l'historique des dépenses
        $user->increment('coins_spent', $request->amount);

        DevilcoinWithdrawal::create([
            'user_id'        => $user->id,
            'amount'         => $request->amount,
            'xmr_address'    => $request->xmr_address,
            'status'         => 'pending',
        ]);

        return redirect()->route('user.withdrawals.index')->with('success', 'Withdrawal request submitted!');
    }
}


