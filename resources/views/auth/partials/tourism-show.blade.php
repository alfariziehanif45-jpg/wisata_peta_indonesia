{{-- 
    Partial Pertunjukan Wisata Nusantara.
    $variant: 'idle' | 'open' | 'close' | 'error'
--}}
@php
    $autoplay = $autoplay ?? false;
    $sub = $sub ?? null;
@endphp

@once
<style>
    /* ===== Panggung Wisata ===== */
    .tourism-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: flex; flex-direction: column; align-items: center; justify-content: flex-end;
        background: radial-gradient(circle at 50% 30%, #1c2541 0%, #0b132b 80%);
        overflow: hidden;
        perspective: 1000px;
    }
    .tourism-overlay.is-skipped { display: none !important; }

    /* Lampu Sorot (Matahari Senja) */
    .tourism-overlay::before {
        content: ''; position: absolute; top: -10vh; left: 50%; transform: translateX(-50%);
        width: 80vw; height: 100vh;
        background: radial-gradient(ellipse at top, rgba(252, 163, 17, 0.15) 0%, transparent 60%);
        pointer-events: none; z-index: 0;
    }

    /* Lantai Dermaga Kayu */
    .tourism-floor {
        position: absolute; bottom: 0; left: 0; right: 0; height: 25vh;
        background: linear-gradient(180deg, #3e2723 0%, #1b0f0a 100%);
        border-top: 6px solid #fca311;
        box-shadow: inset 0 20px 50px rgba(0,0,0,0.9);
        z-index: 1;
        transform: rotateX(10deg);
        transform-origin: bottom;
    }
    .tourism-floor::before {
        content: ''; position: absolute; inset: 0;
        background: repeating-linear-gradient(90deg, transparent 0 40px, rgba(0,0,0,0.6) 40px 42px);
    }

    /* Rumbai Tirai Bambu */
    .tourism-valance {
        position: absolute; z-index: 4; top: 0; left: 0; right: 0; height: 12vh;
        background: linear-gradient(180deg, #4e342e, #2d1b16);
        border-bottom: 6px solid #fca311;
        box-shadow: 0 10px 40px rgba(0,0,0,0.8);
        border-radius: 0 0 50% 50% / 0 0 30px 30px;
    }
    .tourism-valance::after {
        content: ''; position: absolute; left: 0; right: 0; top: 100%; height: 30px;
        background: radial-gradient(circle at 30px 0, #fca311 10px, transparent 11px) 0 0 / 60px 30px repeat-x;
    }

    /* Tirai Bambu */
    .tourism-curtain {
        position: absolute; z-index: 3; top: 0; bottom: 0; width: 52%;
        background: repeating-linear-gradient(90deg, #3e2723 0 15px, #5d4037 15px 30px, #2d1b16 30px 45px);
        border-bottom: 6px solid #fca311;
        transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tourism-curtain-l { left: 0; box-shadow: inset -40px 0 60px rgba(0,0,0,0.8); transform-origin: left center; }
    .tourism-curtain-r { right: 0; box-shadow: inset 40px 0 60px rgba(0,0,0,0.8); transform-origin: right center; }

    /* ===== Teks Marquee ===== */
    .tourism-line {
        position: absolute; z-index: 5; left: 0; right: 0; top: 20%;
        margin: 0; padding: 20px;
        text-align: center;
        font: 700 clamp(1.5rem, 4vw, 2.2rem)/1.25 'Georgia', serif;
        letter-spacing: 2px; color: #fca311;
        text-shadow: 0 0 15px #fca311, 0 0 30px #e85d04, 0 4px 10px #000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.8s ease;
        background: radial-gradient(ellipse at center, rgba(0,0,0,0.6) 0%, transparent 70%);
        border-radius: 50px;
    }
    .tourism-open.is-playing .tourism-line { animation: textGlowIn 1s forwards 1s; }
    .tourism-close.is-playing .tourism-line { animation: textGlowIn 1s forwards 0.5s; }

    /* ===== Tombol Lewati (Tiket Pesawat) ===== */
    .tourism-skip {
        position: absolute; z-index: 10; bottom: 40px; right: 40px;
        padding: 14px 34px;
        background: linear-gradient(135deg, #fca311 0%, #e85d04 100%);
        color: #fff;
        border: 2px solid #fff3d4;
        border-radius: 8px;
        font-family: 'Georgia', serif;
        font-size: 1rem;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0,0,0,0.6), inset 0 0 15px rgba(255,255,255,0.4);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    .tourism-skip::before {
        content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
        transition: left 0.5s ease;
    }
    .tourism-skip:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(252, 163, 17, 0.8), inset 0 0 15px rgba(255,255,255,0.8);
    }
    .tourism-skip:hover::before { left: 200%; }
    .tourism-skip::after {
        content: ''; position: absolute; inset: 4px;
        border: 1px dashed rgba(0,0,0,0.3); border-radius: 4px; pointer-events: none;
    }
    .tourism-skip span { position: relative; z-index: 1; }

    /* ===== Posisi Karakter Turis ===== */
    .tourism-stage {
        position: relative; z-index: 2;
        width: min(420px, 92vw); height: 60vh;
        display: flex; align-items: flex-end; justify-content: center;
        margin-bottom: 5vh;
    }
    .tourism-man { width: min(230px, 54vw); margin-bottom: 14px; }
    
    /* ===== SVG Turis ===== */
    .tourist { width: 100%; height: auto; overflow: visible; }
    .tourist * { transform-box: fill-box; }
    .tourist .t-body { transform-box: view-box; transform-origin: 100px 196px; }
    .tourist .t-head { transform-box: view-box; transform-origin: 100px 90px; }
    .tourist .t-arm-r { transform-box: view-box; transform-origin: 135px 106px; }
    .tourist .t-eyes { transform-origin: 50% 50%; animation: blink 4.5s infinite; }
    .tourist .t-sad-face, .tourist .t-confuse-mark { opacity: 0; }

    /* ===== ANIMASI IDLE ===== */
    .tourism-idle-wrapper .tourism-stage { height: 100%; margin-bottom: 20px; }
    .tourism-idle-wrapper .tourism-floor {
        height: 40px; transform: none; border-radius: 50%; width: 80%; left: 10%;
        border: 2px solid #fca311; border-top: none;
        background: radial-gradient(ellipse at center, rgba(0,0,0,0.8) 0%, transparent 70%);
        box-shadow: none;
    }
    .tourism-idle-wrapper .tourism-floor::before { display: none; }
    .tourism-idle-wrapper .t-body { animation: breathe 3s infinite ease-in-out; }
    .tourism-idle-wrapper .t-head { animation: idleLook 6s infinite ease-in-out; }
    .tourism-idle-wrapper .t-arm-r { animation: handWave 3s ease-in-out infinite; }
    .tourism-idle-wrapper .t-eyes { animation: blink 4s infinite, eyeMove 8s infinite; }

    /* ===== BUKA PERTUNJUKAN ===== */
    .tourism-open { display: none; }
    .tourism-open.is-playing { display: flex; animation: fadeIn .5s ease-out forwards; }
    
    .tourism-open .tourism-curtain-l { transform: translateX(0); }
    .tourism-open .tourism-curtain-r { transform: translateX(0); }
    .tourism-open.is-playing .tourism-curtain-l { animation: curtainL 1.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards; }
    .tourism-open.is-playing .tourism-curtain-r { animation: curtainR 1.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards; }
    
    .tourism-open.is-playing .t-head { animation: nod 1.6s ease-in-out 1.5s 1; }
    .tourism-open.is-playing .t-arm-r { animation: handWave 1.5s ease-in-out 1.5s infinite; }

    /* ===== TUTUP PERTUNJUKAN ===== */
    .tourism-close { display: none; }
    .tourism-close.is-playing { display: flex; animation: fadeIn .5s ease-out forwards; }

    .tourism-close .tourism-curtain-l { transform: translateX(-85%); }
    .tourism-close .tourism-curtain-r { transform: translateX(85%); }
    .tourism-close.is-playing .tourism-curtain-l { animation: closeL 1.5s cubic-bezier(0.4, 0, 0.2, 1) 2.5s forwards; }
    .tourism-close.is-playing .tourism-curtain-r { animation: closeR 1.5s cubic-bezier(0.4, 0, 0.2, 1) 2.5s forwards; }

    .tourism-close.is-playing .t-arm-r { animation: handWave 1.5s ease-in-out 0.5s infinite; }

    /* ===== ERROR ===== */
    .tourism-error {
        display: none;
        background: radial-gradient(ellipse at 50% 42%, #9d0208 0%, transparent 62%), linear-gradient(180deg, #0b132bf2 0%, #1c2541f2 100%);
        transition: opacity .35s ease;
    }
    .tourism-error.is-playing { display: flex; animation: fadeIn .35s ease-out; }
    .tourism-error.is-leaving { opacity: 0; }
    .tourism-error .tourism-curtain, .tourism-error .tourism-valance, .tourism-error .tourism-floor { display: none; }

    .tourism-error .tourism-line {
        top: auto; bottom: calc(50% + min(380px, 70vh) / 2 - 14px);
        width: fit-content; max-width: min(400px, 90vw); margin: 0 auto; padding: 14px 20px;
        background: #fff3d4; color: #5c0b1a; text-shadow: none; border: 2px solid #e85d04; border-radius: 16px;
        box-shadow: 0 10px 30px #000a; font-size: clamp(1rem, 4vw, 1.3rem); transform-origin: 50% 100%;
        opacity: 0; transform: none; background: #fff3d4;
    }
    .tourism-error .tourism-line::after {
        content: ''; position: absolute; left: 50%; bottom: -10px; margin-left: -10px;
        border: 10px solid transparent; border-top-color: #e85d04; border-bottom: 0;
    }
    .tourism-error .tourism-sub { display: block; margin-top: 6px; font: 400 .85rem/1.4 'Trebuchet MS', sans-serif; color: #7a2b1a; }
    
    .tourism-error .tourism-skip {
        top: auto; right: auto; bottom: 8%; left: 50%; transform: translateX(-50%);
        padding: 14px 34px; border: 2px solid #fff3d4; background: linear-gradient(135deg, #fca311 0%, #e85d04 100%); color: #fff; font-weight: 700;
        font-size: 1rem; box-shadow: 0 6px 20px rgba(0,0,0,0.6); cursor: pointer; border-radius: 8px; overflow: visible;
    }
    .tourism-error .tourism-skip::before { display: none; }
    .tourism-error .tourism-skip::after { inset: 4px; }
    .tourism-error .tourism-skip:hover { transform: translateX(-50%) translateY(-3px) scale(1.05); }

    .tourism-error.is-playing .tourism-line { animation: bubbleIn .5s cubic-bezier(.2, 1.4, .4, 1) .7s forwards; }
    .tourism-error.is-playing .t-sad-face { animation: popIn .01s linear .5s forwards; opacity: 1; }
    .tourism-error.is-playing .t-happy-face { animation: popOut .01s linear .5s forwards; }
    .tourism-error.is-playing .t-head { animation: shakeNo 1.1s ease-in-out .7s 1; }
    .tourism-error.is-playing .t-confuse-mark { animation: qPop .6s cubic-bezier(.2, 1.5, .4, 1) .8s both; opacity: 1; }
    .tourism-error.is-playing .t-body { animation: slump .8s ease-out .6s forwards; }

    /* ===== Keyframes ===== */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes curtainL { from { transform: translateX(0); } to { transform: translateX(-85%); } }
    @keyframes curtainR { from { transform: translateX(0); } to { transform: translateX(85%); } }
    @keyframes closeL { from { transform: translateX(-85%); } to { transform: translateX(0); } }
    @keyframes closeR { from { transform: translateX(85%); } to { transform: translateX(0); } }
    
    @keyframes textGlowIn { to { opacity: 1; transform: translateY(0); } }
    @keyframes bubbleIn { from { opacity: 0; transform: scale(.7) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes popIn { to { opacity: 1; } }
    @keyframes popOut { to { opacity: 0; } }
    @keyframes blink { 0%, 93%, 100% { transform: scaleY(1); } 96% { transform: scaleY(.08); } }
    @keyframes nod { 0%, 100% { transform: rotate(0); } 25% { transform: rotate(4deg); } 60% { transform: rotate(-3deg); } }
    @keyframes shakeNo { 0%, 100% { transform: rotate(0); } 15% { transform: rotate(-9deg); } 35% { transform: rotate(8deg); } 55% { transform: rotate(-7deg); } 75% { transform: rotate(5deg); } }
    @keyframes slump { to { transform: translateY(4px) rotate(-2deg); } }
    @keyframes qPop { 0% { opacity: 0; transform: scale(0) rotate(-30deg); } 70% { opacity: 1; transform: scale(1.2) rotate(8deg); } 100% { opacity: 1; transform: scale(1) rotate(0); } }

    @keyframes breathe { 0%, 100% { transform: scaleY(1) translateY(0); } 50% { transform: scaleY(1.02) translateY(-2px); } }
    @keyframes idleLook { 0%, 100% { transform: rotate(0); } 25% { transform: rotate(3deg); } 75% { transform: rotate(-3deg); } }
    @keyframes handWave { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(-20deg); } }
    @keyframes eyeMove { 0%, 100% { transform: translateX(0); } 20% { transform: translateX(1.5px); } 40% { transform: translateX(-1.5px); } 60% { transform: translateX(0); } }

    @media (prefers-reduced-motion: reduce) {
        .tourism-open { display: none !important; }
        .tourist * { animation: none !important; }
        .tourism-error .tourism-line { opacity: 1; }
    }
</style>
@endonce

@if ($variant === 'idle')
    <!-- Mode Idle: Turis Menunggu -->
    <div class="tourism-idle-wrapper">
        <div class="tourism-stage">
            <div class="tourism-floor"></div>
            <div class="tourism-man">
                <!-- SVG Turis -->
                <svg class="tourist" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="288" rx="54" ry="7" fill="#000" opacity=".35"/>
                    <g class="t-body">
                        <!-- Kaki -->
                        <rect x="85" y="200" width="12" height="70" rx="4" fill="#3a2418"/>
                        <rect x="103" y="200" width="12" height="70" rx="4" fill="#3a2418"/>
                        <ellipse cx="91" cy="275" rx="14" ry="6" fill="#1b1330"/>
                        <ellipse cx="109" cy="275" rx="14" ry="6" fill="#1b1330"/>
                        
                        <!-- Badan & Celana Pendek -->
                        <path d="M75 130 L125 130 L128 200 L72 200 Z" fill="#2d6a4f"/>
                        <rect x="72" y="195" width="56" height="15" rx="4" fill="#1b4332"/>
                        
                        <!-- Kemeja (Polo) -->
                        <path d="M70 105 Q75 90 100 90 Q125 90 130 105 L125 140 L75 140 Z" fill="#e85d04"/>
                        
                        <!-- Tas Punggung (Tali) -->
                        <path d="M75 105 L85 150" stroke="#3a2418" stroke-width="6" fill="none"/>
                        <path d="M125 105 L115 150" stroke="#3a2418" stroke-width="6" fill="none"/>
                        <rect x="75" y="140" width="50" height="50" rx="8" fill="#3a2418" opacity="0.9"/>

                        <!-- Lengan Kiri (Memegang Peta) -->
                        <rect x="55" y="110" width="15" height="50" rx="7" fill="#e85d04"/>
                        <circle cx="62" cy="160" r="7" fill="#f4cfa9"/>
                        <path d="M50 160 L75 160 L70 175 L55 175 Z" fill="#fff3d4"/>
                        <path d="M50 160 L60 165 L70 160" stroke="#ccc" stroke-width="1" fill="none"/>

                        <!-- Kepala -->
                        <g class="t-head">
                            <!-- Leher -->
                            <rect x="93" y="85" width="14" height="10" rx="4" fill="#e0b08c"/>
                            <!-- Wajah -->
                            <ellipse cx="100" cy="65" rx="20" ry="22" fill="#f4cfa9"/>
                            <!-- Mata -->
                            <g class="t-eyes">
                                <circle cx="92" cy="64" r="2.5" fill="#2a1a14"/>
                                <circle cx="108" cy="64" r="2.5" fill="#2a1a14"/>
                            </g>
                            <!-- Hidung -->
                            <path d="M100 66 Q103 72 98 73" stroke="#d29a74" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                            <!-- Senyum -->
                            <path class="t-happy-face" d="M92 78 Q100 84 108 78" stroke="#8a3a2a" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <!-- Wajah Sedih -->
                            <path class="t-sad-face" d="M92 82 Q100 76 108 82" stroke="#8a3a2a" stroke-width="2" fill="none" stroke-linecap="round"/>
                            
                            <!-- Topi Rimba -->
                            <path d="M75 55 Q100 20 125 55" fill="#d4a373"/>
                            <path d="M70 55 L130 55" stroke="#b5835a" stroke-width="6" stroke-linecap="round"/>
                            
                            <!-- Tanda Tanya (Error) -->
                            <text class="t-confuse-mark" x="135" y="40" font-family="Georgia, serif" font-size="28" font-weight="700" fill="#fca311">?</text>
                        </g>

                        <!-- Lengan Kanan (Melambai) -->
                        <g class="t-arm-r">
                            <rect x="130" y="110" width="15" height="50" rx="7" fill="#e85d04"/>
                            <circle cx="138" cy="160" r="7" fill="#f4cfa9"/>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
    </div>
@else
    <!-- Mode Overlay: Error, Open, Close -->
    <div class="tourism-overlay tourism-{{ $variant }} {{ $autoplay ? 'is-playing' : '' }}" id="{{ $id }}" role="{{ $variant === 'error' ? 'alert' : 'presentation' }}">
        <button type="button" class="tourism-skip" id="{{ $id }}Skip" aria-label="{{ $variant === 'error' ? 'Tutup pesan dan coba lagi' : 'Lewati animasi' }}">
            <span>{{ $variant === 'error' ? 'Coba Lagi' : 'Lewati' }}</span>
        </button>
        
        <div class="tourism-valance"></div>
        <div class="tourism-curtain tourism-curtain-l"></div>
        <div class="tourism-curtain tourism-curtain-r"></div>
        <div class="tourism-floor"></div>

        <p class="tourism-line">{{ $line }}@if ($sub)<span class="tourism-sub">{{ $sub }}</span>@endif</p>
        
        <div class="tourism-stage">
            <div class="tourism-man">
                <!-- SVG Turis (Sama seperti di atas) -->
                <svg class="tourist" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="100" cy="288" rx="54" ry="7" fill="#000" opacity=".35"/>
                    <g class="t-body">
                        <rect x="85" y="200" width="12" height="70" rx="4" fill="#3a2418"/>
                        <rect x="103" y="200" width="12" height="70" rx="4" fill="#3a2418"/>
                        <ellipse cx="91" cy="275" rx="14" ry="6" fill="#1b1330"/>
                        <ellipse cx="109" cy="275" rx="14" ry="6" fill="#1b1330"/>
                        <path d="M75 130 L125 130 L128 200 L72 200 Z" fill="#2d6a4f"/>
                        <rect x="72" y="195" width="56" height="15" rx="4" fill="#1b4332"/>
                        <path d="M70 105 Q75 90 100 90 Q125 90 130 105 L125 140 L75 140 Z" fill="#e85d04"/>
                        <path d="M75 105 L85 150" stroke="#3a2418" stroke-width="6" fill="none"/>
                        <path d="M125 105 L115 150" stroke="#3a2418" stroke-width="6" fill="none"/>
                        <rect x="75" y="140" width="50" height="50" rx="8" fill="#3a2418" opacity="0.9"/>
                        <rect x="55" y="110" width="15" height="50" rx="7" fill="#e85d04"/>
                        <circle cx="62" cy="160" r="7" fill="#f4cfa9"/>
                        <path d="M50 160 L75 160 L70 175 L55 175 Z" fill="#fff3d4"/>
                        <path d="M50 160 L60 165 L70 160" stroke="#ccc" stroke-width="1" fill="none"/>
                        <g class="t-head">
                            <rect x="93" y="85" width="14" height="10" rx="4" fill="#e0b08c"/>
                            <ellipse cx="100" cy="65" rx="20" ry="22" fill="#f4cfa9"/>
                            <g class="t-eyes">
                                <circle cx="92" cy="64" r="2.5" fill="#2a1a14"/>
                                <circle cx="108" cy="64" r="2.5" fill="#2a1a14"/>
                            </g>
                            <path d="M100 66 Q103 72 98 73" stroke="#d29a74" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                            <path class="t-happy-face" d="M92 78 Q100 84 108 78" stroke="#8a3a2a" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <path class="t-sad-face" d="M92 82 Q100 76 108 82" stroke="#8a3a2a" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <path d="M75 55 Q100 20 125 55" fill="#d4a373"/>
                            <path d="M70 55 L130 55" stroke="#b5835a" stroke-width="6" stroke-linecap="round"/>
                            <text class="t-confuse-mark" x="135" y="40" font-family="Georgia, serif" font-size="28" font-weight="700" fill="#fca311">?</text>
                        </g>
                        <g class="t-arm-r">
                            <rect x="130" y="110" width="15" height="50" rx="7" fill="#e85d04"/>
                            <circle cx="138" cy="160" r="7" fill="#f4cfa9"/>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
    </div>
@endif

@if ($variant === 'open')
<script>
    (function () {
        var overlay = document.getElementById('{{ $id }}');
        var skip = document.getElementById('{{ $id }}Skip');
        if (!overlay) return;
        function closeShow() { overlay.classList.add('is-skipped'); }
        skip.addEventListener('click', closeShow);
        setTimeout(closeShow, 5000);
    })();
</script>
@endif

@if ($variant === 'error')
<script>
    (function () {
        var overlay = document.getElementById('{{ $id }}');
        var btn = document.getElementById('{{ $id }}Skip');
        if (!overlay) return;
        var hideTimer = null, leaveTimer = null;

        function hide() {
            clearTimeout(hideTimer);
            overlay.classList.add('is-leaving');
            leaveTimer = setTimeout(function () {
                overlay.classList.remove('is-playing', 'is-leaving');
                var pw = document.querySelector('input[type="password"]');
                if (pw) { pw.focus(); pw.select(); }
            }, 350);
        }
        overlay.tourismPlay = function () {
            clearTimeout(hideTimer);
            clearTimeout(leaveTimer);
            overlay.classList.remove('is-playing', 'is-leaving');
            void overlay.offsetWidth;
            overlay.classList.add('is-playing');
            hideTimer = setTimeout(hide, 6500);
            btn.focus();
        };
        btn.addEventListener('click', hide);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && overlay.classList.contains('is-playing')) hide(); });
        @if ($autoplay) overlay.tourismPlay(); @endif
    })();
</script>
@endif