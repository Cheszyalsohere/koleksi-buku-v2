<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
public function login(Request $request)
    {
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {

        $request->session()->regenerate();
        return redirect()->intended('/');

        // === OTP FLOW (uncomment jika ingin aktifkan OTP) ===
        // $user = Auth::user();
        // $otp = rand(100000, 999999);
        // $user->update(['otp' => $otp]);
        // Auth::logout();
        // session(['otp_user_id' => $user->id]);
        // Mail::raw("Kode OTP login kamu: $otp", function ($message) use ($user) {
        //     $message->to($user->email)->subject('Kode OTP Login');
        // });
        // return redirect('/otp');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.'
    ]);
    }

    public function verifyOtp(Request $request)
    {
    $user = \App\Models\User::find(session('otp_user_id'));

    if (!$user) {
        return back()->with('error', 'Session OTP hilang');
    }

    if ($request->otp == $user->otp) {

        $user->update(['otp' => null]);

        Auth::login($user);

        session()->forget('otp_user_id');

        return redirect('/');
    }

    return back()->with('error', 'OTP salah');
    }

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
