<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /* ---------------------------- REGISTER ---------------------------- */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Hapus URL "intended" lama agar tidak nyasar ke endpoint API
        $request->session()->forget('url.intended');

        return redirect()->route('map.index');
    }

    /* ----------------------------- LOGIN ------------------------------ */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ]);
        }

        $request->session()->regenerate();

        // Jika URL tujuan sebelumnya adalah endpoint API / AJAX, abaikan
        $intended = $request->session()->get('url.intended', '');
        if (str_contains($intended, '/api/') || str_contains($intended, 'favorite-places')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('map.index'));
    }

    /* ----------------------------- LOGOUT ----------------------------- */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('map.index');
    }
}