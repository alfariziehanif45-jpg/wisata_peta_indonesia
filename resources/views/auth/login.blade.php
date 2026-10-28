@extends('auth.layout')
@section('title', 'Masuk')
@section('heading', 'Masuk ke akun')
@section('content')

{{-- Pesulap menutup pertunjukan: diputar saat tombol "Masuk" ditekan --}}
@include('auth.partials.magic-show', [
    'id'      => 'loginOutro',
    'variant' => 'close',
    'line'    => 'Terima kasih sudah login!',
])

<form method="POST" action="{{ route('login.store') }}" id="loginForm">
    @csrf
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
    <label for="password">Kata sandi</label>
    <input id="password" type="password" name="password" required autocomplete="current-password">
    <label style="display:flex;align-items:center;gap:8px;font-weight:400"><input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya</label>
    <button class="submit" type="submit" id="loginBtn">Masuk</button>
</form>
<p class="bottom">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>

<script>
    (function () {
        var form = document.getElementById('loginForm');
        var btn = document.getElementById('loginBtn');
        var outro = document.getElementById('loginOutro');
        var skip = document.getElementById('loginOutroSkip');
        var sending = false;
        var timer = null;

        function send() {
            clearTimeout(timer);
            form.submit();
        }

        // Event "submit" hanya terpanggil jika validasi browser (required, email) sudah lolos
        form.addEventListener('submit', function (e) {
            if (sending) return;
            e.preventDefault();
            sending = true;

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                send();
                return;
            }

            btn.disabled = true;
            outro.classList.add('is-playing');
            timer = setTimeout(send, 3600); // tunggu animasi selesai, lalu kirim form
        });

        skip.addEventListener('click', send);

        // Jika user menekan tombol Back, kembalikan halaman seperti semula
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                sending = false;
                btn.disabled = false;
                outro.classList.remove('is-playing');
            }
        });
    })();
</script>
@endsection