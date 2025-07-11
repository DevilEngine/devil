<?php

namespace App\Http\Controllers\Swap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\StealthExService;

use App\Models\Swap\SwapTransaction;

use CryptoQr\BitcoinQr;
use CryptoQr\CryptoQr;
use CryptoQr\Qr;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

use Auth;
use DB;

class SwapAdminController extends Controller
{
    public function all(){

        $totalSwappingSend = SwapTransaction::where('status','finished')->where('fromCurrency','xmr')->sum('amount_to_send');
        $totalSwappingReceive = SwapTransaction::where('status','finished')->where('toCurrency','xmr')->sum('amount_to_receive');
        //$totalCommissions = SwapTransaction::where('status','finished')->get()->sum(fn($transaction) => $transaction->commission_in_usd);

        $cCountProgress = SwapTransaction::where('status','waiting')
                        ->orWhere('status','confirming')
                        ->orWhere('status','exchanging')
                        ->orWhere('status','sending')
                        ->orWhere('status','verifying')
                    ->count();
        
        $cCountFinished = SwapTransaction::where('status','finished')
                        ->where('status','finished')
                        ->count();

        $cCountRefunded = SwapTransaction::where('status','refunded')
                        ->where('status','refunded')
                        ->count();
        $cCountExpired = SwapTransaction::where('status','expired')
                        ->where('status','expired')
                        ->count();
        $cCountFailed = SwapTransaction::where('status','failed')
                        ->where('status','failed')
                        ->count();

        if(request()->has('query') && request()->has('status')){

            $query = request()->input('query');
            $status = request()->input('status');

            if(request()->input('status') == "all"){
                $swaps = SwapTransaction::where('token','like', "%$query%")
                ->orWhere('identifier','like', "%$query%")
                ->orderBy('id','DESC')
                ->paginate(30);
            }else{
                $swaps = SwapTransaction::where('token','like', "%$query%")
                ->where('status', 'like',"%$status%")
                ->orWhere('identifier','like', "%$query%")
                ->where('status', 'like',"%$status%")
                ->orderBy('id','DESC')
                ->paginate(30);
            }
        
        }else{

            $swaps = SwapTransaction::orderBy('created_at','DESC')->paginate(30);

        }

        return view('admin.swap.all')->with(compact('swaps','totalSwappingSend','totalSwappingReceive','cCountProgress','cCountFinished','cCountExpired','cCountRefunded','cCountExpired','cCountFailed'));

    }

    /*
    public function swapAdmin($token){

        $swap = SwapTransaction::where('identifier', $token)->firstOrFail();

        return view('admin.swap.swap')->with(compact('swap'));

    }

    public function tombolaAll(){

        $totalTombolas = Tombola::select(DB::raw('count(distinct won_at) as total'))->first()->total;
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $tombola_week = Tombola::where('category', 'weekly')->orderBy('won_at', 'desc')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->paginate(20);

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $tombola_month = Tombola::where('category', 'monthly')->orderBy('won_at', 'desc')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->paginate(20);

        $totalXMR = (Tombola::where('category', 'weekly')->count() * 0.1) + (Tombola::where('category', 'monthly')->count() * 0.5);

        return view('admin.swap.tombola.all')->with(compact('tombola_week','tombola_month','totalTombolas','totalXMR'));

    }

    public function tombolaParticipantAll(){

        // Récupérer les participants de la semaine en cours
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyParticipants = SwapTransaction::whereNotNull('tombola_recipient')
            ->where('status', 'finished')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->paginate(20);

        // Récupérer les participants du mois en cours
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthlyParticipants = SwapTransaction::whereNotNull('tombola_recipient')
            ->where('status', 'finished')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->paginate(20);

        $cParticipants = SwapTransaction::whereNotNull('tombola_recipient')
        ->where('status', 'finished')
        ->count();

        $cWinners = Tombola::count();

        return view('admin.swap.tombola.participants')->with(compact('weeklyParticipants','monthlyParticipants','cParticipants','cWinners'));

    }

    public function tombolaFormWinner($id){

        $tombola = Tombola::where('id', $id)->firstOrFail();

        return view('admin.swap.tombola.tombola')->with(compact('tombola'));

    }

    public function tombolaSendTxid(Request $request, $id){

        $tombola = Tombola::where('id', $id)->firstOrFail();

        $request->validate([
            'txid' => 'required|string',
        ]);

        Tombola::where('id', $id)->update(['txid' => $request->input('txid')]);

        return redirect(route('admin.tombola.all'))->with('success','Transaction ID send');

    }

    */
}
