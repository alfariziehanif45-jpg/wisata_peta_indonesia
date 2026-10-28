@extends('auth.layout')
@section('title', 'Masuk')
@section('theme', 'theme-login')
@section('kicker', 'Pertunjukan Dimulai')
@section('heading', 'Masuk ke akun')
@section('subtitle', 'Sorot lampu menunggu Anda. Naiklah ke panggung.')

@section('card_top')
    <div class="corner tl">A<small>♠</small></div>
    <div class="corner br">A<small>♠</small></div>
@endsection

@section('content')

<style>
    @keyframes cardShake {
        0%, 100% { transform: translateX(0); }
        15% { transform: translateX(-10px); }
        35% { transform: translateX(9px); }
        55% { transform: translateX(-7px); }
        75% { transform: translateX(5px); }
    }
    .card.is-shaking { animation: cardShake .5s ease-in-out; }
    .theme-login form input.has-error {
        border-color: #ff7a8f; box-shadow: 0 0 0 3px #ff7a8f33;
    }
</style>

{{-- Pesulap sedih: tampil jika email / kata sandi salah (otomatis dari server, atau lewat JS) --}}
@include('auth.partials.magic-show', [
    'id'       => 'loginError',
    'variant'  => 'error',
    'line'     => 'Waduh, email atau kata sandi salah!',
    'sub'      => 'Periksa lagi email dan kata sandi Anda, lalu coba sekali lagi.',
    'autoplay' => $errors->any(),
])

{{-- Pesulap menutup pertunjukan: diputar HANYA setelah login berhasil --}}
@include('auth.partials.magic-show', [
    'id'      => 'loginOutro',
    'variant' => 'close',
    'line'    => 'Terima kasih sudah login!',
])

{{-- Kotak pesan (ditampilkan lewat JS saat login gagal tanpa reload) --}}
<div class="alert" id="loginInlineError" role="alert" hidden>Email atau kata sandi salah. Silakan coba lagi.</div>

<form method="POST" action="{{ route('login.store') }}" id="loginForm">
    @csrf
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="@error('email') has-error @enderror">

    <label for="password">Kata sandi</label>
    <input id="password" type="password" name="password" required autocomplete="current-password" class="@error('email') has-error @enderror">

    <label style="display:flex;align-items:center;gap:8px;font-weight:400;text-transform:none;letter-spacing:0;color:#d9ccff">
        <input type="checkbox" name="remember" value="1" style="width:auto"> Ingat saya
    </label>

    <button class="submit" type="submit" id="loginBtn">Naik ke Panggung</button>
</form>

<p class="bottom">Belum punya tiket? <a href="{{ route('register') }}">Daftar sekarang</a></p>

<script>
    (function () {
        var form = document.getElementById('loginForm');
        var btn = document.getElementById('loginBtn');
        var emailEl = document.getElementById('email');
        var passEl = document.getElementById('password');
        var outro = document.getElementById('loginOutro');
        var skip = document.getElementById('loginOutroSkip');
        var errOverlay = document.getElementById('loginError');
        var inlineErr = document.getElementById('loginInlineError');
        var card = document.querySelector('.card');
        var label = btn.textContent;
        var sending = false;
        var target = null;
        var timer = null;

        function go() {
            clearTimeout(timer);
            if (target) window.location.href = target;
        }

        function nativeSubmit() {
            HTMLFormElement.prototype.submit.call(form); // cadangan: kirim form biasa
        }

        function reset() {
            sending = false;
            btn.disabled = false;
            btn.textContent = label;
        }

        // Login gagal: pesulap sedih, kartu bergetar, kolom ditandai merah
        function showFailure() {
            reset();
            passEl.value = '';
            emailEl.classList.add('has-error');
            passEl.classList.add('has-error');
            inlineErr.hidden = false;
            if (card) {
                card.classList.remove('is-shaking');
                void card.offsetWidth;
                card.classList.add('is-shaking');
            }
            if (errOverlay && errOverlay.magicPlay) errOverlay.magicPlay();
        }

        // Login berhasil: pesulap berterima kasih, tirai menutup, lalu pindah halaman
        function showSuccess(url) {
            target = url;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { go(); return; }
            outro.classList.add('is-playing');
            timer = setTimeout(go, 3600); // tunggu tirai menutup (±3,4 dtk)
        }

        // Event "submit" hanya terpanggil jika validasi browser (required, email) sudah lolos
        form.addEventListener('submit', function (e) {
            if (sending) return;
            e.preventDefault();
            sending = true;
            btn.disabled = true;
            btn.textContent = 'Memeriksa tiket...';
            inlineErr.hidden = true;
            emailEl.classList.remove('has-error');
            passEl.classList.remove('has-error');

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin',
                redirect: 'follow',
                headers: { 'Accept': 'text/html' }
            }).then(function (res) {
                if (!res.redirected) { nativeSubmit(); return; }       // respon tak terduga
                var finalUrl = new URL(res.url, window.location.href);
                if (finalUrl.pathname === window.location.pathname) {
                    showFailure();                                      // dikembalikan ke /login = gagal
                } else {
                    showSuccess(res.url);                               // diarahkan ke halaman lain = berhasil
                }
            }).catch(function () {
                nativeSubmit();                                         // jaringan bermasalah: kirim biasa
            });
        });

        skip.addEventListener('click', go);

        // Jika user menekan tombol Back, kembalikan halaman seperti semula
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                reset();
                outro.classList.remove('is-playing');
            }
        });
    })();
</script>
@endsection