<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /** Arahkan user ke halaman persetujuan Google. */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /** Terima balasan Google, lalu daftarkan / login-kan user. */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('login')
                ->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Hubungkan akun lama (daftar via email) dengan Google
            if (! $user->google_id) {
                $user->google_id = $googleUser->getId();
            }
            if (! $user->email_verified_at) {
                $user->email_verified_at = now();
            }
            $user->save();
        } else {
            // User baru: buat akun otomatis (role default tetap "pengguna")
            $user = User::create([
                'name'      => $googleUser->getName() ?: 'Pengguna',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password'  => Str::random(40), // otomatis di-hash oleh cast 'hashed'
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('map.index'));
    }
}