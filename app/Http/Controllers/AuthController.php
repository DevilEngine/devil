<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\CaptchaService;

use Log;

class AuthController extends Controller
{

    protected $captchaService;

    public function __construct(CaptchaService $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    // Affichage du formulaire d'inscription
    public function showRegisterForm()
    {
        $captcha = app(CaptchaService::class)->generateCaptcha();
        return view('auth.register')->with(compact('captcha'));
    }

    // Inscription de l'utilisateur
    public function register(Request $request)
    {

        if ($request->filled('check_user')) {
            return abort(403, 'Bot detected');
        }

        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->route('register')->withErrors([
                'captcha' => 'Trop de tentatives, veuillez réessayer plus tard.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->route('register')->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        $mnemonic = User::generateMnemonic();

        session()->put('mnemonic', $mnemonic);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'mnemonic_key' => Hash::make($mnemonic),
        ]);

        $user = User::where('username', $request->input('username'))->firstOrFail();

        session()->put('user', $user->username);

        return redirect(route('register.mnemonic'))->with('success','Register with success, now save your mnemonic key!');
    }

    public function mnemonic(){

        if(!session()->has('mnemonic')){
            return redirect()->back()->with('error','You cant access to this page !');
        }

        return view('auth.mnemonic');
    }

    public function saveMnemonic(){

        if(!session()->has('mnemonic')){
            return redirect()->back()->with('error','You cant access to this page !');
        }

        session()->forget('mnemonic');

        $user = User::where('username', session()->get('user'))->firstOrFail();

        Auth::login($user);

        return redirect(route('home'));

    }

    // Affichage du formulaire de connexion
    public function showLoginForm()
    {
        $captcha = app(CaptchaService::class)->generateCaptcha();
        return view('auth.login')->with(compact('captcha'));
    }

    public function login(Request $request)
    {

        if ($request->filled('check_user')) {
            return abort(403, 'Bot detected');
        }

        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'ID incorrect']);
        }

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->route('login')->withErrors([
                'captcha' => 'Trop de tentatives, veuillez réessayer plus tard.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->route('login')->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        Auth::login($user);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
        
            if ($user->banned) {
                Auth::logout();
        
                return redirect()->route('login')->withErrors([
                    'username' => 'Your account has been banned.',
                ]);
            }
        }

        return redirect()->route('home');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function showRecoverForm()
    {
        $captcha = app(CaptchaService::class)->generateCaptcha();
        return view('auth.recover')->with(compact('captcha'));
    }

    // Récupération de compte avec clé mnémonique
    public function recover(Request $request)
    {

        if ($request->filled('check_user')) {
            return abort(403, 'Bot detected');
        }

        $request->validate([
            'username' => 'required',
            'mnemonic' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->mnemonic, $user->mnemonic_key)) {
            return back()->withErrors(['error' => 'Recovery key incorrect for this user']);
        }

        $captcha = app(CaptchaService::class);

        if ($captcha->isCaptchaBlocked()) {
            return redirect()->route('recover')->withErrors([
                'captcha' => 'Trop de tentatives, veuillez réessayer plus tard.'
            ]);
        }
        
        if (!$captcha->verifyClick((int) $request->input('captcha_x'), (int) $request->input('captcha_y'))) {
            $captcha->registerCaptchaAttempt();
            return redirect()->route('recover')->withErrors([
                'captcha' => 'CAPTCHA incorrect.'
            ]);
        }
        
        $captcha->resetCaptchaAttempts();

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password updated with succcess !');
    }
}
