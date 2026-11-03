@extends('auth.layout')
@section('title', 'Masuk')
@section('heading', 'Masuk ke akun')
@section('subtitle', 'Jelajahi keindahan Nusantara. Mulai perjalanan Anda.')

@section('content')
<div class="stage-container">
    <div class="stage-left">
        @include('auth.partials.nusantara-show', ['variant' => 'idle', 'id' => 'loginIdle'])
    </div>

    <div class="stage-right" id="loginFormContainer">
        <h1>Masuk ke akun</h1>
        <p>Jelajahi keindahan Nusantara. Mulai perjalanan Anda.</p>

        @include('auth.partials.nusantara-show', [
            'id'       => 'loginError',
            'variant'  => 'error',
            'line'     => 'Waduh, sepertinya panen Anda gagal!',
            'sub'      => 'Periksa kembali email dan kata sandi Anda.',
            'autoplay' => $errors->any(),
        ])

        @include('auth.partials.nusantara-show', [
            'id'      => 'loginOutro',
            'variant' => 'close',
            'line'    => 'Terima kasih telah bertani di Nusantara!',
        ])

        <div class="alert" id="loginInlineError" role="alert" hidden style="background:#fdecea;color:#b3261e;border:1px solid #f5c6c2;border-radius:8px;padding:10px 12px;margin-bottom:14px;font-size:14px;">Email atau kata sandi salah. Silakan coba lagi.</div>

        <form method="POST" action="{{ route('login.store') }}" id="loginForm">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="@error('email') has-error @enderror">

            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="@error('email') has-error @enderror">

            <label style="display:flex;align-items:center;gap:8px;font-weight:400;text-transform:none;letter-spacing:0;color:#e0d8c3;margin-bottom:20px;">
                <input type="checkbox" name="remember" value="1" style="width:auto;margin:0;"> Ingat saya
            </label>

            <button class="submit" type="submit" id="loginBtn">Mulai Bertani</button>
        </form>

        <p class="bottom">Belum punya lahan? <a href="{{ route('register') }}">Daftar sekarang</a></p>
    </div>
</div>

<style>
    @keyframes cardShake { 0%, 100% { transform: translateX(0); } 15% { transform: translateX(-10px); } 35% { transform: translateX(9px); } 55% { transform: translateX(-7px); } 75% { transform: translateX(5px); } }
    .stage-right.is-shaking { animation: cardShake .5s ease-in-out; }
    input.has-error { border-color: #ff7a8f !important; box-shadow: 0 0 0 3px #ff7a8f33 !important; }
</style>

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
        var card = document.getElementById('loginFormContainer');
        var label = btn.textContent;
        var sending = false;
        var target = null;
        var timer = null;

        function go() { clearTimeout(timer); if (target) window.location.href = target; }
        function nativeSubmit() { HTMLFormElement.prototype.submit.call(form); }
        function reset() { sending = false; btn.disabled = false; btn.textContent = label; }

        function showFailure() {
            reset();
            passEl.value = '';
            emailEl.classList.add('has-error');
            passEl.classList.add('has-error');
            inlineErr.hidden = false;
            if (card) { card.classList.remove('is-shaking'); void card.offsetWidth; card.classList.add('is-shaking'); }
            if (errOverlay && errOverlay.nusaPlay) errOverlay.nusaPlay();
        }

        function showSuccess(url) {
            target = url;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { go(); return; }
            outro.classList.add('is-playing');
            timer = setTimeout(go, 3600);
        }

        form.addEventListener('submit', function (e) {
            if (sending) return;
            e.preventDefault();
            sending = true;
            btn.disabled = true;
            btn.textContent = 'Memeriksa lahan...';
            inlineErr.hidden = true;
            emailEl.classList.remove('has-error');
            passEl.classList.remove('has-error');

            fetch(form.action, {
                method: 'POST', body: new FormData(form), credentials: 'same-origin', redirect: 'follow', headers: { 'Accept': 'text/html' }
            }).then(function (res) {
                if (!res.redirected) { nativeSubmit(); return; }
                var finalUrl = new URL(res.url, window.location.href);
                if (finalUrl.pathname === window.location.pathname) { showFailure(); } else { showSuccess(res.url); }
            }).catch(function () { nativeSubmit(); });
        });

        skip.addEventListener('click', go);
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) { reset(); outro.classList.remove('is-playing'); }
        });
    })();
</script>
@endsection