@extends('auth.layout')
@section('title', 'Daftar')
@section('heading', 'Buat akun baru')
@section('content')

{{-- Pesulap membuka pertunjukan: tirai terbuka, menyapa, lalu halaman daftar muncul --}}
@include('auth.partials.magic-show', [
    'id'      => 'regIntro',
    'variant' => 'open',
    'line'    => 'Selamat datang di pertunjukan!',
])

{{-- Pesan error dari proses login Google --}}
@if (session('error'))
    <div class="alert alert-error" role="alert">{{ session('error') }}</div>
@endif

{{-- Daftar / masuk langsung dengan Google --}}
<a class="google-btn" href="{{ route('google.redirect') }}">
    <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
    </svg>
    <span>Daftar dengan Google</span>
</a>

<div class="divider"><span>atau daftar dengan email</span></div>

<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <label for="name">Nama lengkap</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
    <label for="password">Kata sandi (minimal 8 karakter)</label>
    <input id="password" type="password" name="password" required autocomplete="new-password">
    <label for="password_confirmation">Konfirmasi kata sandi</label>
    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
    <button class="submit" type="submit">Daftar</button>
</form>
<p class="bottom">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>

<style>
    .alert-error {
        background: #fdecea;
        color: #b3261e;
        border: 1px solid #f5c6c2;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 14px;
        font-size: 14px;
    }
    .google-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 11px 14px;
        background: #fff;
        color: #3c4043;
        border: 1px solid #dadce0;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        text-decoration: none;
        transition: background .15s, box-shadow .15s;
        box-sizing: border-box;
    }
    .google-btn:hover {
        background: #f8f9fa;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
    }
    .divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 18px 0;
        color: #80868b;
        font-size: 13px;
    }
    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #dadce0;
    }
</style>
@endsection