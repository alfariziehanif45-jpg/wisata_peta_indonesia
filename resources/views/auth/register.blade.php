@extends('auth.layout')
@section('title', 'Daftar')
@section('heading', 'Buat akun baru')

@section('content')
<div id="darkScreen" class="dark-screen" role="dialog" aria-modal="true" aria-label="Tarik karcis untuk memulai perjalanan">
    <div class="ticket-slot">
        <div class="slot-hole"></div>
        <div class="ticket" id="ticket">
            <div class="ticket-content">
                <span class="plane-icon">🌾</span> <!-- Ikon Padi -->
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
        @include('auth.partials.nusantara-show', ['variant' => 'idle', 'id' => 'regIdle'])
    </div>

    <div class="stage-right">
        <h1>Buat akun baru</h1>
        <p>Ambil karcis Anda dan mulailah petualangan.</p>

        @if (session('error'))
            <div class="alert alert-error" role="alert" style="background: #fdecea; color: #b3261e; border: 1px solid #f5c6c2; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; font-size: 14px;">{{ session('error') }}</div>
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
    </div>
</div>

<style>
    /* CSS Dasar & Reset */
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        padding: 0;
        background-color: #0d1715;
        font-family: 'Poppins', sans-serif;
    }

    /* Layout Utama: Dikunci 100vh agar pas 1 layar */
    .stage-container {
        display: flex;
        height: 100vh; 
        width: 100%;
        overflow: hidden; /* Mencegah halaman scroll */
    }

    /* Bagian Kiri (Ilustrasi) */
    .stage-left {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #13211e;
        border-right: 2px solid rgba(212, 175, 55, 0.3);
        position: relative;
        height: 100%;
    }

    /* Bagian Kanan (Form) */
    .stage-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center; /* Ditengah secara vertikal */
        padding: 30px 10%; /* Jarak kiri-kanan dan atas-bawah dirapatkan */
        height: 100%;
        overflow-y: auto; /* Jika layar sangat kecil (seperti laptop mini), baru muncul scroll internal */
        color: #e0e0e0;
    }

    /* Mengatur Ulang Spasi (Margin) agar muat banyak elemen */
    .stage-right h1 {
        margin: 0 0 8px 0;
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
    }

    .stage-right p {
        color: #8c9e98;
        margin: 0 0 16px 0;
        font-size: 14px;
    }

    /* Tombol Google */
    .google-btn {
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 10px; 
        width: 100%; 
        padding: 10px 14px; 
        background: #fff; 
        color: #3c4043; 
        border: 1px solid #dadce0; 
        border-radius: 8px; 
        font-size: 14px; 
        font-weight: 500; 
        text-decoration: none; 
        font-family: 'Poppins', sans-serif;
        transition: background 0.2s;
    }
    .google-btn:hover { background: #f8f9fa; }

    /* Divider */
    .divider {
        display: flex; 
        align-items: center; 
        gap: 12px; 
        margin: 14px 0; /* Margin dirapatkan */
        color: #8c9e98; 
        font-size: 12px;
    }
    .divider .line {
        flex: 1; 
        height: 1px; 
        background: #2d4a3e;
    }

    /* Form Elements */
    form {
        display: flex;
        flex-direction: column;
    }

    .stage-right label {
        font-size: 11px;
        font-weight: 600;
        color: #b0c4b9;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
        margin-top: 10px; /* Jarak antar input dirapatkan */
    }

    .stage-right input {
        width: 100%;
        padding: 10px 12px;
        background-color: #08100e;
        border: 1px solid #2d4a3e;
        border-radius: 8px;
        color: #ffffff;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        transition: border-color 0.3s;
    }

    .stage-right input:focus {
        outline: none;
        border-color: #d4af37;
        box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
    }

    .stage-right .submit {
        width: 100%;
        padding: 12px;
        margin-top: 18px;
        background-color: #d4af37;
        color: #08100e;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.3s;
    }

    .stage-right .submit:hover {
        background-color: #e5c158;
    }

    .stage-right .bottom {
        margin-top: 16px;
        margin-bottom: 0;
        text-align: center;
        font-size: 13px;
        color: #8c9e98;
    }

    .stage-right .bottom a {
        color: #d4af37;
        text-decoration: none;
        font-weight: 600;
    }
    .stage-right .bottom a:hover {
        text-decoration: underline;
    }

    /* CSS Animasi Tiket (Bawaan) */
    .dark-screen {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; height: 100dvh;
        z-index: 99999; background: #08100e; overflow: hidden; opacity: 1; transition: opacity 1.1s ease;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        user-select: none; -webkit-user-select: none;
        background-image: radial-gradient(circle at 10px 10px, rgba(212, 175, 55, 0.05) 2px, transparent 2.5px);
        background-size: 40px 40px;
    }
    .dark-screen.reveal { opacity: 0; pointer-events: none; }
    body.is-dark { overflow: hidden; }

    .ticket-slot {
        position: relative; width: 280px; height: 130px; background: #11241e;
        border: 4px solid #d4af37; border-radius: 8px;
        box-shadow: inset 0 10px 20px rgba(0,0,0,0.8), 0 0 30px rgba(212, 175, 55, 0.2);
        overflow: hidden; display: flex; justify-content: center;
    }
    .slot-hole {
        position: absolute; top: 0; width: 100%; height: 15px; background: #08100e;
        border-bottom: 3px solid #d4af37; border-radius: 0 0 10px 10px; z-index: 2;
    }
    .ticket {
        position: absolute; top: 10px; width: 240px; height: 220px;
        background: linear-gradient(135deg, #fdfbf7, #f0e6d2);
        border-radius: 6px; border: 1px dashed #aa8c2c;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.5);
        cursor: grab; touch-action: none; transition: transform 0.1s;
        animation: ticketBob 2s ease-in-out infinite;
    }
    .ticket.touched { animation: none; }
    .ticket.dragging { cursor: grabbing; transition: none; }
    .ticket-content { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; }
    .plane-icon { font-size: 45px; color: #8b0000; }
    .ticket-text { font-weight: bold; color: #3e2723; letter-spacing: 3px; font-family: 'Playfair Display', serif; font-size: 1.1rem; }
    .barcode { width: 160px; height: 30px; background: repeating-linear-gradient(90deg, #000 0 2px, transparent 2px 4px, #000 4px 6px, transparent 6px 10px); margin-top: 10px; }
    @keyframes ticketBob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
    .dark-text { margin-top: 40px; text-align: center; color: #8c9e98; font-size: 16px; letter-spacing: 1px; transition: opacity .4s ease; font-family: 'Poppins', sans-serif; }
    .dark-screen.on .dark-text { opacity: 0; }

    /* Responsif */
    @media (max-width: 768px) {
        .stage-container { flex-direction: column; overflow: auto; }
        .stage-left { display: none; }
        .stage-right { padding: 30px 20px; height: auto; overflow-y: visible; }
    }
</style>

<noscript><style>.dark-screen{display:none !important} #mainStage{opacity:1 !important}</style></noscript>

<script>
    (function () {
        var dark  = document.getElementById('darkScreen');
        var ticket = document.getElementById('ticket');
        var stage = document.getElementById('mainStage');
        var regIntro = document.getElementById('regIntro');
        var started = false, dragging = false, startY = 0, dy = 0;
        var MAX_PULL = 150, TRIGGER = 100;

        document.body.appendChild(dark);
        document.body.classList.add('is-dark');

        function setPull(px) { ticket.style.transform = `translateY(${px}px)`; }

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
            setTimeout(function () { startShow(); }, 300);
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