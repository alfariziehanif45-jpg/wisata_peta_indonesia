@extends('auth.layout')
@section('title', 'Daftar')
@section('heading', 'Buat akun baru')
@section('content')
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
@endsection
