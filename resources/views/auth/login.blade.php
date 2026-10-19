@extends('auth.layout')
@section('title', 'Masuk')
@section('heading', 'Masuk ke akun')
@section('content')
<form method="POST" action="{{ route('login.store') }}">
    @csrf
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
    <label for="password">Kata sandi</label>
    <input id="password" type="password" name="password" required autocomplete="current-password">
    <label style="display:flex;align-items:center;gap:8px;font-weight:400"><input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya</label>
    <button class="submit" type="submit">Masuk</button>
</form>
<p class="bottom">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
@endsection
