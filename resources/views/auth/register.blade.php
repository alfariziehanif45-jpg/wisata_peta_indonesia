@extends('auth.layout')
@section('title', 'Daftar')
@section('heading', 'Buat akun baru')

@section('styles')
<style>
    .stage-right { padding-top: 36px; padding-bottom: 36px; }
    .stage-right label { margin-top: 2px; }
    .stage-right input { margin-bottom: 12px; padding: 11px 14px; }
    .stage-right .submit { margin-top: 8px; }
    .stage-right .hint { margin: -6px 0 12px; font-size: .75rem; color: var(--muted); }

    /* ============ Intro karcis ============ */
    .dark-screen {
        position: fixed; inset: 0; width: 100vw; height: 100vh; height: 100dvh;
        z-index: 99999; background: #08100e; overflow: hidden; opacity: 1; transition: opacity 1.1s ease;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        user-select: none; -webkit-user-select: none;
        background-image: radial-gradient(circle at 10px 10px, rgba(212,175,55,.06) 2px, transparent 2.5px);
        background-size: 40px 40px;
    }
    .dark-screen.reveal { opacity: 0; pointer-events: none; }

    .ticket-slot {
        position: relative; width: 280px; height: 130px; background: #11241e;
        border: 4px solid var(--gold); border-radius: 8px;
        box-shadow: inset 0 10px 20px rgba(0,0,0,.8), 0 0 40px rgba(212,175,55,.25);
        overflow: hidden; display: flex; justify-content: center;
    }
    .slot-hole {
        position: absolute; top: 0; width: 100%; height: 15px; background: #08100e;
        border-bottom: 3px solid var(--gold); border-radius: 0 0 10px 10px; z-index: 2;
    }
    .ticket {
        position: absolute; top: 10px; width: 240px; height: 220px;
        background: linear-gradient(135deg, #fdfbf7, #f0e6d2);
        border-radius: 6px; border: 1px dashed var(--gold-dark);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 10px 20px rgba(0,0,0,.5);
        cursor: grab; touch-action: none; transition: transform .1s;
        animation: ticketBob 2s ease-in-out infinite;
    }
    .ticket:focus-visible { outline: 3px solid var(--gold); outline-offset: 4px; }
    .ticket.touched { animation: none; }
    .ticket.dragging { cursor: grabbing; transition: none; }
    .ticket-content { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; }
    .plane-icon { font-size: 45px; }
    .ticket-text { font-weight: 700; color: #3e2723; letter-spacing: 3px; font-family: 'Playfair Display', serif; font-size: 1.1rem; }
    .barcode { width: 160px; height: 30px; margin-top: 10px; background: repeating-linear-gradient(90deg, #000 0 2px, transparent 2px 4px, #000 4px 6px, transparent 6px 10px); }
    @keyframes ticketBob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
    .dark-text { margin-top: 40px; text-align: center; color: var(--muted); font-size: 16px; letter-spacing: 1px; transition: opacity .4s ease; }
    .dark-screen.on .dark-text { opacity: 0; }
    @media (prefers-reduced-motion: reduce) { .ticket { animation: none; } }
</style>
@endsection

@section('content')
<div id="darkScreen" class="dark-screen" role="dialog" aria-modal="true" aria-label="Tarik karcis untuk memulai perjalanan">
    <div class="ticket-slot">
        <div class="slot-hole"></div>
        <div class="ticket" id="ticket" tabindex="0" role="button" aria-label="Tarik karcis atau tekan Enter">
            <div class="ticket-content">
                <span class="plane-icon" aria-hidden="true">🌾</span>
                <span class="ticket-text">TARIK KARCIS</span>
                <div class="barcode"></div>
            </div>
        </div>
    </div>
    <p class="dark-text" id="darkText">Petualangan menanti… tarik karcis Anda</p>
</div>

@include('auth.partials.nusantara-show', [
    'id'      => 'regIntro',
    'variant' => 'open',
    'line'    => 'Selamat datang di Tanah Nusantara!',
])

<div class="stage-container" id="mainStage">
    <div class="stage-left">
        @include('auth.partials.nusantara-scene', ['id' => 'regScene', 'start' => 'pantai'])
    </div>

    <div class="stage-right">
        <h1>Buat akun baru</h1>
        <p>Ambil karcis Anda dan mulailah petualangan.</p>

        @if (session('error'))
            <div class="alert" role="alert">{{ session('error') }}</div>
        @endif

        <a class="google-btn" href="{{ route('google.redirect') }}">
            <svg width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            <span>Daftar dengan Google</span>
        </a>

        <div class="divider">
            <span class="line"></span>
            <span>atau daftar dengan email</span>
            <span class="line"></span>
        </div>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf
            <label for="name">Nama lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Nama Anda" class="@error('name') has-error @enderror">

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com" class="@error('email') has-error @enderror">

            <label for="password">Kata sandi</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="@error('password') has-error @enderror">

            <label for="password_confirmation">Konfirmasi kata sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">

            <button class="submit" type="submit">Daftar</button>
        </form>
        <p class="bottom">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </div>
</div>

<noscript><style>.dark-screen { display: none !important; }</style></noscript>

<script>
    (function () {
        var dark = document.getElementById('darkScreen');
        var ticket = document.getElementById('ticket');
        var regIntro = document.getElementById('regIntro');
        var started = false, dragging = false, startY = 0, dy = 0;
        var MAX_PULL = 150, TRIGGER = 100;

        document.body.appendChild(dark);
        document.body.classList.add('is-dark');

        function setPull(px) { ticket.style.transform = 'translateY(' + px + 'px)'; }

        function startShow() {
            if (started) return;
            started = true;
            dark.classList.add('on');
            setTimeout(function () {
                dark.classList.add('reveal');
                document.body.classList.remove('is-dark');
                if (regIntro) regIntro.classList.add('is-playing');
                setTimeout(function () { if (dark.parentNode) dark.parentNode.removeChild(dark); }, 1300);
            }, 500);
        }

        function autoPull() {
            if (started) return;
            ticket.classList.add('touched');
            setPull(TRIGGER + 30);
            setTimeout(startShow, 300);
        }

        ticket.addEventListener('pointerdown', function (e) {
            if (started) return;
            dragging = true; dy = 0; startY = e.clientY;
            ticket.classList.add('dragging', 'touched');
            ticket.setPointerCapture(e.pointerId);
            e.preventDefault();
        });

        ticket.addEventListener('pointermove', function (e) {
            if (!dragging) return;
            dy = Math.max(0, Math.min(MAX_PULL, e.clientY - startY));
            setPull(dy);
        });

        function endDrag() {
            if (!dragging) return;
            dragging = false;
            ticket.classList.remove('dragging');
            if (dy >= TRIGGER) { startShow(); }
            else if (dy < 10) { autoPull(); }
            else { setPull(0); }
        }

        ticket.addEventListener('pointerup', endDrag);
        ticket.addEventListener('pointercancel', endDrag);
        ticket.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); autoPull(); }
        });
        ticket.focus({ preventScroll: true });
    })();
</script>
@endsection