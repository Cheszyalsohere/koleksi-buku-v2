<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->redirect();
    }

    public function handleGoogleCallback()
    {
    $googleUser = Socialite::driver('google')
        ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
        ->stateless()
        ->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'id_google' => $googleUser->getId(),
            'password' => bcrypt(Str::random(16))
        ]
    );

    // 🔥 Generate OTP
    $otp = rand(100000, 999999);
    $user->update(['otp' => $otp]);

    // Simpan ID user ke session (BELUM login)
    session(['otp_user_id' => $user->id]);

    // Kirim OTP ke email
    Mail::raw("Kode OTP login kamu: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Kode OTP Login');
    });

    return redirect('/otp');

    }
}