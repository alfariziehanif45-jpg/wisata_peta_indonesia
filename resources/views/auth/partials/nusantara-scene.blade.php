{{--
    Pemandangan Nusantara berganti-ganti: sawah & desa -> pantai -> gunung -> malam desa.
    Pemakaian: @include('auth.partials.nusantara-scene', ['id' => 'loginScene'])
    Opsi: 'interval' (ms, default 9000), 'start' (sawah|pantai|gunung|malam)
--}}
@php
    $id = $id ?? 'nsc';
    $interval = $interval ?? 9000;
    $start = $start ?? 'sawah';
    $scenes = [
        ['key' => 'sawah',  'icon' => '🌾', 'chip' => 'Sawah',  'title' => 'Sawah terasering & desa', 'place' => 'Ubud, Bali'],
        ['key' => 'pantai', 'icon' => '🏝️', 'chip' => 'Pantai', 'title' => 'Pantai & perahu jukung',  'place' => 'Gili Trawangan, Lombok'],
        ['key' => 'gunung', 'icon' => '🌋', 'chip' => 'Gunung', 'title' => 'Fajar di lautan pasir',    'place' => 'Bromo, Jawa Timur'],
        ['key' => 'malam',  'icon' => '🌙', 'chip' => 'Malam',  'title' => 'Malam desa & kunang-kunang', 'place' => 'Kampung di kaki bukit'],
    ];
@endphp

@once
<style>
    .nsc { position: absolute; inset: 0; --mx: 0; --my: 0; color: #fff; font-family: 'Poppins', sans-serif; overflow: hidden; }
    .nsc-scene { position: absolute; inset: 0; opacity: 0; transform: scale(1.07); transform-origin: 50% 100%; transition: opacity 1.6s ease, transform 2.8s cubic-bezier(.2,.7,.2,1); pointer-events: none; }
    .nsc-scene.is-active { opacity: 1; transform: scale(1); }
    .nsc-scene svg { position: absolute; inset: 0; width: 100%; height: 100%; display: block; }

    /* parallax mengikuti kursor */
    .nsc .d1, .nsc .d2, .nsc .d3 { transition: transform .6s ease-out; will-change: transform; }
    .nsc .d1 { transform: translate3d(calc(var(--mx) * -5px),  calc(var(--my) * -2px), 0); }
    .nsc .d2 { transform: translate3d(calc(var(--mx) * -10px), calc(var(--my) * -4px), 0); }
    .nsc .d3 { transform: translate3d(calc(var(--mx) * -18px), calc(var(--my) * -6px), 0); }

    /* animasi elemen */
    .nsc .cloud { animation: nscDrift 70s ease-in-out infinite alternate; }
    .nsc .cloud.slow { animation-duration: 110s; animation-direction: alternate-reverse; }
    .nsc .twinkle { animation: nscTwinkle 3.2s ease-in-out infinite alternate; }
    .nsc .bird { animation: nscFly 24s linear infinite; }
    .nsc .bird.b2 { animation-duration: 32s; animation-delay: -14s; }
    .nsc .wave.w1 { animation: nscWave 11s linear infinite; }
    .nsc .wave.w2 { animation: nscWave 8s linear infinite reverse; }
    .nsc .wave.w3 { animation: nscWave 6s linear infinite; }
    .nsc .foam { animation: nscFoam 4s ease-in-out infinite alternate; }
    .nsc .bob { animation: nscBob 4.5s ease-in-out infinite alternate; transform-box: fill-box; transform-origin: center bottom; }
    .nsc .smoke { animation: nscSmoke 8s ease-out infinite; transform-box: fill-box; transform-origin: center bottom; opacity: 0; }
    .nsc .smoke.s2 { animation-delay: -4s; }
    .nsc .mist { animation: nscMist 16s ease-in-out infinite alternate; }
    .nsc .mist.m2 { animation-duration: 22s; animation-direction: alternate-reverse; }
    .nsc .stalk { animation: nscSway 3.4s ease-in-out infinite alternate; transform-box: fill-box; transform-origin: center bottom; }
    .nsc .ff { animation: nscFirefly 6s ease-in-out infinite alternate; filter: drop-shadow(0 0 4px #d8ff7a); }
    .nsc .flick { animation: nscFlick 2.6s ease-in-out infinite alternate; }
    .nsc .sun-glow { animation: nscGlow 6s ease-in-out infinite alternate; transform-box: fill-box; transform-origin: center; }

    @keyframes nscDrift   { from { transform: translateX(-40px); } to { transform: translateX(70px); } }
    @keyframes nscTwinkle { from { opacity: .2; } to { opacity: 1; } }
    @keyframes nscFly     { from { transform: translateX(-60px); } to { transform: translateX(700px); } }
    @keyframes nscWave    { from { transform: translateX(0); } to { transform: translateX(-150px); } }
    @keyframes nscFoam    { from { transform: translateY(0); opacity: .9; } to { transform: translateY(-9px); opacity: .35; } }
    @keyframes nscBob     { from { transform: translateY(0) rotate(-1.5deg); } to { transform: translateY(-5px) rotate(1.5deg); } }
    @keyframes nscSmoke   { 0% { transform: translateY(0) scale(.5); opacity: 0; } 20% { opacity: .55; } 100% { transform: translateY(-70px) scale(1.8); opacity: 0; } }
    @keyframes nscMist    { from { transform: translateX(-50px); } to { transform: translateX(50px); } }
    @keyframes nscSway    { from { transform: rotate(-5deg); } to { transform: rotate(5deg); } }
    @keyframes nscFirefly { 0% { transform: translate(0, 0); opacity: .2; } 50% { opacity: 1; } 100% { transform: translate(18px, -26px); opacity: .3; } }
    @keyframes nscFlick   { from { opacity: .75; } to { opacity: 1; } }
    @keyframes nscGlow    { from { transform: scale(.94); opacity: .8; } to { transform: scale(1.06); opacity: 1; } }

    /* lapisan UI di atas pemandangan */
    .nsc-scrim { position: absolute; inset: 0; pointer-events: none;
        background: linear-gradient(180deg, rgba(4,10,8,.55) 0%, rgba(4,10,8,0) 28%, rgba(4,10,8,0) 70%, rgba(4,10,8,.6) 100%); }
    .nsc-brand { position: absolute; top: 28px; left: 32px; right: 32px; z-index: 3; text-shadow: 0 2px 10px rgba(0,0,0,.55); }
    .nsc-brand strong { display: block; font-family: 'Playfair Display', serif; font-size: clamp(1.4rem, 2.4vw, 1.9rem); font-weight: 700; line-height: 1.1; }
    .nsc-brand span { display: block; margin-top: 4px; font-size: .85rem; font-weight: 300; opacity: .9; }

    .nsc-ui { position: absolute; left: 24px; right: 24px; bottom: 22px; z-index: 3; display: flex; flex-direction: column; gap: 12px; }
    .nsc-cap { text-shadow: 0 2px 8px rgba(0,0,0,.6); min-height: 44px; }
    .nsc-cap-title { font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 600; }
    .nsc-cap-place { font-size: .8rem; font-weight: 300; opacity: .9; }
    .nsc-chips { display: flex; gap: 8px; flex-wrap: wrap; }
    .nsc-chip {
        position: relative; overflow: hidden; display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 999px; cursor: pointer;
        font: 500 .8rem 'Poppins', sans-serif; color: #fff;
        background: rgba(8,16,14,.45); border: 1px solid rgba(255,255,255,.28);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        transition: background .25s, border-color .25s, transform .1s;
    }
    .nsc-chip:hover { background: rgba(8,16,14,.7); }
    .nsc-chip:active { transform: scale(.97); }
    .nsc-chip:focus-visible { outline: 3px solid #fff; outline-offset: 2px; }
    .nsc-chip.is-active { background: rgba(212,175,55,.9); border-color: #d4af37; color: #08100e; }
    .nsc-chip.is-active::after {
        content: ''; position: absolute; left: 0; bottom: 0; height: 3px; width: 100%;
        background: #08100e; transform-origin: left; animation: nscProgress var(--dur, 9s) linear forwards;
    }
    .nsc:hover .nsc-chip.is-active::after { animation-play-state: paused; }
    @keyframes nscProgress { from { transform: scaleX(0); } to { transform: scaleX(1); } }

    /* ===== Karakter turis & teman lokal berdiri di atas pemandangan ===== */
    .nsc { container-type: size; }
    .nsc-chars {
        --mw: clamp(70px, 19cqh, 150px);   /* lebar turis */
        --fw: clamp(64px, 17cqh, 136px);   /* lebar teman lokal */
        position: absolute; left: 0; right: 0; bottom: 118px; height: min(40cqh, 330px);
        z-index: 4; pointer-events: none;
    }
    .nsc-chars .tourist { pointer-events: auto; transition: filter 1.8s ease; }
    .nsc-chars .nusa-idle-wrapper { width: 100%; height: 100%; }
    .nsc .nsc-chars .nusa-stage { width: 100%; height: 100%; margin: 0; padding: 0; }
    .nsc-chars .nusa-floor { display: none; }
    .nsc .nsc-chars .nusa-man { width: var(--mw); margin: 0 0 6px; }
    .nsc .nsc-chars .nusa-walker {
        width: var(--fw); bottom: 6px;
        left: calc(50% - var(--mw) / 2 - var(--fw) - 4px);   /* berdiri tepat di sebelah turis */
    }
    .nsc .nsc-chars .nusa-idle-wrapper.has-visitor .nusa-man { transform: translateX(10px); }
    .nsc-chars .t-speech { font-size: clamp(.68rem, 1.9cqh, .86rem); max-width: clamp(130px, 38cqw, 210px); padding: 8px 12px; }

    /* Cahaya karakter mengikuti waktu di pemandangan, ikut memudar bersama latar */
    .nsc[data-scene="sawah"]  .nsc-chars .tourist { filter: brightness(1)   saturate(1)   sepia(0)   hue-rotate(0deg); }
    .nsc[data-scene="pantai"] .nsc-chars .tourist { filter: brightness(1.08) saturate(1.12) sepia(0)   hue-rotate(0deg); }
    .nsc[data-scene="gunung"] .nsc-chars .tourist { filter: brightness(.92) saturate(1.05) sepia(.35) hue-rotate(-12deg); }
    .nsc[data-scene="malam"]  .nsc-chars .tourist { filter: brightness(.64) saturate(.75)  sepia(.2)  hue-rotate(170deg); }

    /* ===== Pakaian karakter berganti mengikuti tempat ===== */
    .nsc[data-scene="sawah"]  { --t-sa:#2a9d8f; --t-sb:#1b6359; --t-pa:#e9c46a; --t-pb:#c29c41; --t-shoe:#f1f1f1; --t-band:#2a9d8f;
                                --f-sa:#a63a24; --f-sb:#5e1710; --f-pa:#2f3142; --f-pb:#1a1b27; --f-shoe:#6b3f22; --f-motif:.85; }
    .nsc[data-scene="pantai"] { --t-sa:#ff9f43; --t-sb:#e0612b; --t-pa:#f2d9a0; --t-pb:#d6b46e; --t-shoe:#ff7f50; --t-band:#ff5d8f;
                                --f-sa:#35b4d9; --f-sb:#1b7ea0; --f-pa:#d9c08a; --f-pb:#b39a62; --f-shoe:#c9a15c; --f-motif:.85; }
    .nsc[data-scene="gunung"] { --t-sa:#e76f51; --t-sb:#9c3a22; --t-pa:#4a5568; --t-pb:#2d3748; --t-shoe:#6b4a2f; --t-band:#2a9d8f;
                                --f-sa:#587a5a; --f-sb:#2c4230; --f-pa:#2f3a36; --f-pb:#1a2420; --f-shoe:#3e2a1a; --f-motif:0; }
    .nsc[data-scene="malam"]  { --t-sa:#3a5a8c; --t-sb:#1f3358; --t-pa:#2b3447; --t-pb:#171d2b; --t-shoe:#2a2f3a; --t-band:#2a9d8f;
                                --f-sa:#f3eee2; --f-sb:#cfc7b2; --f-pa:#2f3142; --f-pb:#1a1b27; --f-shoe:#5a3a22; --f-motif:0; }

    .nsc-chars stop { transition: stop-color 1.4s ease; }
    .nsc-chars .tourist:not(.friend) linearGradient[id^="shirtGrad"] stop:first-child { stop-color: var(--t-sa); }
    .nsc-chars .tourist:not(.friend) linearGradient[id^="shirtGrad"] stop:last-child  { stop-color: var(--t-sb); }
    .nsc-chars .tourist:not(.friend) linearGradient[id^="pantsGrad"] stop:first-child { stop-color: var(--t-pa); }
    .nsc-chars .tourist:not(.friend) linearGradient[id^="pantsGrad"] stop:last-child  { stop-color: var(--t-pb); }
    .nsc-chars .friend linearGradient[id^="fShirt"] stop:first-child { stop-color: var(--f-sa); }
    .nsc-chars .friend linearGradient[id^="fShirt"] stop:last-child  { stop-color: var(--f-sb); }
    .nsc-chars .friend linearGradient[id^="fPants"] stop:first-child { stop-color: var(--f-pa); }
    .nsc-chars .friend linearGradient[id^="fPants"] stop:last-child  { stop-color: var(--f-pb); }
    .nsc-chars .tourist:not(.friend) .t-foot-l > path:nth-child(3),
    .nsc-chars .tourist:not(.friend) .t-foot-r > path:nth-child(3) { fill: var(--t-shoe); transition: fill 1.4s ease; }
    .nsc-chars .friend .t-foot-l > path:nth-child(3),
    .nsc-chars .friend .t-foot-r > path:nth-child(3) { fill: var(--f-shoe); transition: fill 1.4s ease; }
    .nsc-chars .friend .t-body > g[fill="#e9c46a"] { opacity: var(--f-motif); transition: opacity 1.2s ease; }
    .nsc-chars .tourist:not(.friend) .base-hat:nth-child(2) { fill: var(--t-band); transition: fill 1.4s ease; }

    /* aksesori & bentuk pakaian tambahan (disuntik lewat JS) memudar masuk/keluar */
    .nsc-chars .ov { opacity: 0; transition: opacity 1s ease; pointer-events: none; }
    .nsc[data-scene="sawah"]  .nsc-chars .ov-sawah,
    .nsc[data-scene="pantai"] .nsc-chars .ov-pantai,
    .nsc[data-scene="gunung"] .nsc-chars .ov-gunung,
    .nsc[data-scene="malam"]  .nsc-chars .ov-malam { opacity: 1; }
    .nsc-chars .base-hat { transition: opacity .9s ease; }
    .nsc[data-scene="sawah"]  .nsc-chars .tourist:not(.friend) .base-hat,
    .nsc[data-scene="gunung"] .nsc-chars .tourist:not(.friend) .base-hat,
    .nsc[data-scene="pantai"] .nsc-chars .friend .base-hat { opacity: 0; }
    @keyframes nscLantern { 0%, 100% { opacity: .28; } 50% { opacity: .5; } }
    .nsc-chars .lantern-glow { animation: nscLantern 2.4s ease-in-out infinite; }

    @media (max-width: 900px) {
        .nsc-brand { top: 16px; left: 18px; }
        .nsc-chars { bottom: 54px; height: min(58cqh, 360px); }
        .nsc-brand span { display: none; }
        .nsc-ui { left: 14px; right: 14px; bottom: 12px; gap: 8px; }
        .nsc-cap { display: none; }
        .nsc-chip { padding: 6px 11px; font-size: .74rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .nsc *, .nsc *::after { animation: none !important; transition-duration: .01ms !important; }
        .nsc .smoke { opacity: .35; }
    }
</style>
@endonce

<div class="nsc" id="{{ $id }}" data-interval="{{ $interval }}" data-start="{{ $start }}" role="img" aria-label="Ilustrasi pemandangan Nusantara yang berganti: sawah, pantai, gunung, dan desa di malam hari">

    {{-- Simbol bersama --}}
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <symbol id="{{ $id }}-cloud" viewBox="0 0 120 40">
                <path d="M10 36 Q0 36 4 28 Q8 20 20 22 Q24 8 40 10 Q52 0 64 12 Q80 6 88 20 Q108 18 110 32 Q112 36 104 36Z"/>
            </symbol>
            <symbol id="{{ $id }}-palm" viewBox="0 0 100 160">
                <path d="M50 160 Q55 100 46 52" stroke="#5b3a22" stroke-width="5" fill="none" stroke-linecap="round"/>
                <g fill="currentColor">
                    <path d="M46 52 Q20 32 0 52 Q24 40 46 58Z"/>
                    <path d="M46 52 Q30 22 8 22 Q30 28 46 58Z"/>
                    <path d="M46 52 Q50 16 74 6 Q56 26 50 58Z"/>
                    <path d="M46 52 Q70 32 100 46 Q72 42 48 58Z"/>
                    <path d="M46 52 Q66 58 90 82 Q64 68 46 58Z"/>
                    <path d="M46 52 Q26 60 6 82 Q28 66 46 58Z"/>
                </g>
                <circle cx="44" cy="57" r="3.5" fill="#5b3a22"/>
            </symbol>
            <symbol id="{{ $id }}-house" viewBox="0 0 80 60">
                <rect x="8" y="34" width="64" height="24" style="fill:var(--wall,#d9b88a)"/>
                <path d="M0 38 L14 22 H66 L80 38Z" style="fill:var(--roof,#6b3b24)"/>
                <path d="M16 24 L28 6 H52 L64 24Z" style="fill:var(--roof2,#8a4e30)"/>
                <rect x="34" y="42" width="12" height="16" style="fill:var(--win,#2b1b10)"/>
                <rect x="14" y="43" width="10" height="9" style="fill:var(--win,#2b1b10)"/>
                <rect x="56" y="43" width="10" height="9" style="fill:var(--win,#2b1b10)"/>
            </symbol>
        </defs>
    </svg>

    <div class="nsc-scenes">

        {{-- ================= 1. SAWAH & DESA (senja) ================= --}}
        <div class="nsc-scene" data-key="sawah" data-title="{{ $scenes[0]['title'] }}" data-place="{{ $scenes[0]['place'] }}">
            <svg viewBox="0 0 600 800" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                <defs>
                    <linearGradient id="{{ $id }}-skyA" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#1b3548"/><stop offset=".42" stop-color="#cf6a4c"/>
                        <stop offset=".68" stop-color="#f7c873"/><stop offset="1" stop-color="#ffe7a8"/>
                    </linearGradient>
                    <radialGradient id="{{ $id }}-sunA">
                        <stop offset="0" stop-color="#fff6c8"/><stop offset=".35" stop-color="#ffd37a" stop-opacity=".75"/>
                        <stop offset="1" stop-color="#ffb347" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                <rect width="600" height="800" fill="url(#{{ $id }}-skyA)"/>
                <circle class="sun-glow" cx="400" cy="500" r="190" fill="url(#{{ $id }}-sunA)"/>
                <circle cx="400" cy="500" r="40" fill="#fff3bf"/>
                <use class="cloud" href="#{{ $id }}-cloud" x="30" y="300" width="170" height="57" fill="#ffd9b0" opacity=".55"/>
                <use class="cloud slow" href="#{{ $id }}-cloud" x="360" y="360" width="140" height="47" fill="#ffd9b0" opacity=".45"/>
                <use class="cloud slow" href="#{{ $id }}-cloud" x="200" y="230" width="110" height="37" fill="#f4b9a0" opacity=".4"/>
                <g transform="translate(60,330)"><g class="bird"><path d="M0 0 q6 -7 12 0 q6 -7 12 0" fill="none" stroke="#2b2b2b" stroke-width="1.6" stroke-linecap="round"/></g></g>
                <g transform="translate(0,380)"><g class="bird b2"><path d="M0 0 q5 -6 10 0 q5 -6 10 0" fill="none" stroke="#2b2b2b" stroke-width="1.4" stroke-linecap="round"/></g></g>

                <g class="d1">
                    <path d="M-40 565 L60 485 L120 525 L220 435 L300 525 L380 475 L470 535 L560 485 L640 545 V640 H-40Z" fill="#3b5a55" opacity=".75"/>
                    <path d="M165 590 L288 405 L312 405 L435 590Z" fill="#2c4a47"/>
                    <ellipse class="mist" cx="300" cy="575" rx="330" ry="24" fill="#ffd9a8" opacity=".3"/>
                </g>

                <g class="d2">
                    <path d="M-40 595 Q150 565 300 590 T640 580 V800 H-40Z" fill="#3f7d3a"/>
                    <path d="M-40 595 Q150 565 300 590 T640 580" fill="none" stroke="#b9ea8a" stroke-width="1.5" opacity=".5"/>
                    <use href="#{{ $id }}-house" x="30"  y="548" width="62" height="46"/>
                    <use href="#{{ $id }}-house" x="112" y="565" width="46" height="34" style="--wall:#cfa77a"/>
                    <use href="#{{ $id }}-house" x="430" y="545" width="66" height="49"/>
                    <use href="#{{ $id }}-house" x="508" y="562" width="46" height="34" style="--wall:#cfa77a"/>
                    <path d="M-40 645 Q200 605 380 645 T640 630 V800 H-40Z" fill="#5da545"/>
                    <path d="M-40 645 Q200 605 380 645 T640 630" fill="none" stroke="#c9f09a" stroke-width="1.5" opacity=".55"/>
                    <path d="M60 640 Q140 624 230 642 Q150 658 60 640Z" fill="#ffd9a0" opacity=".55"/>
                    <path d="M400 636 Q470 622 560 636 Q480 650 400 636Z" fill="#ffd9a0" opacity=".5"/>
                    <path d="M-40 705 Q150 672 320 708 T640 693 V800 H-40Z" fill="#7cc04f"/>
                    <path d="M-40 705 Q150 672 320 708 T640 693" fill="none" stroke="#d7f6a8" stroke-width="1.5" opacity=".6"/>
                    @for ($i = 0; $i < 7; $i++)
                        <path d="M-40 {{ 722 + $i * 10 }} Q150 {{ 700 + $i * 10 }} 320 {{ 728 + $i * 10 }} T640 {{ 716 + $i * 10 }}" fill="none" stroke="#a6e07a" opacity=".28"/>
                    @endfor
                </g>

                <g class="d3">
                    <use href="#{{ $id }}-palm" x="-25" y="520" width="135" height="216" style="color:#1f4d2c"/>
                    <use href="#{{ $id }}-palm" x="505" y="565" width="95" height="152" style="color:#255a33"/>
                    @for ($i = 0; $i < 44; $i++)
                        @php $x = -10 + $i * 15; $h = 28 + ($i * 7) % 24; @endphp
                        <path class="stalk" d="M{{ $x }} 805 q3 -{{ $h / 2 }} 0 -{{ $h }}" fill="none" stroke="{{ $i % 2 ? '#b8e86e' : '#8fcf52' }}" stroke-width="2.6" stroke-linecap="round" style="animation-delay:-{{ ($i % 7) * .5 }}s"/>
                    @endfor
                </g>
                <rect y="400" width="600" height="400" fill="#ff9a4d" opacity=".1"/>
            </svg>
        </div>

        {{-- ================= 2. PANTAI (siang) ================= --}}
        <div class="nsc-scene" data-key="pantai" data-title="{{ $scenes[1]['title'] }}" data-place="{{ $scenes[1]['place'] }}">
            <svg viewBox="0 0 600 800" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                <defs>
                    <linearGradient id="{{ $id }}-skyB" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#2f9fd3"/><stop offset=".5" stop-color="#8fd8ee"/><stop offset=".72" stop-color="#ffeec2"/>
                    </linearGradient>
                    <linearGradient id="{{ $id }}-seaB" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#27b9d6"/><stop offset="1" stop-color="#0c6c97"/>
                    </linearGradient>
                    <radialGradient id="{{ $id }}-sunB">
                        <stop offset="0" stop-color="#fffbe0"/><stop offset=".4" stop-color="#fff2a8" stop-opacity=".6"/>
                        <stop offset="1" stop-color="#fff2a8" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                <rect width="600" height="800" fill="url(#{{ $id }}-skyB)"/>
                <circle class="sun-glow" cx="130" cy="300" r="150" fill="url(#{{ $id }}-sunB)"/>
                <circle cx="130" cy="300" r="34" fill="#fffbe0"/>
                <use class="cloud" href="#{{ $id }}-cloud" x="260" y="250" width="190" height="63" fill="#fff" opacity=".9"/>
                <use class="cloud slow" href="#{{ $id }}-cloud" x="40" y="430" width="150" height="50" fill="#fff" opacity=".8"/>
                <use class="cloud slow" href="#{{ $id }}-cloud" x="420" y="410" width="120" height="40" fill="#fff" opacity=".7"/>
                <g transform="translate(100,360)"><g class="bird"><path d="M0 0 q6 -7 12 0 q6 -7 12 0" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/></g></g>
                <g transform="translate(0,410)"><g class="bird b2"><path d="M0 0 q5 -6 10 0 q5 -6 10 0" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></g></g>

                <g class="d1">
                    <path d="M360 545 Q420 495 470 515 Q520 485 640 545Z" fill="#2f7d6a" opacity=".85"/>
                    <rect x="-40" y="545" width="680" height="200" fill="url(#{{ $id }}-seaB)"/>
                </g>

                <g class="d2">
                    <g transform="translate(-40,555)"><g class="wave w1"><path fill="#35bfd9" opacity=".7" d="M0 0 q37.5 -10 75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 V260 H0Z"/></g></g>
                    <g transform="translate(-40,590)"><g class="wave w2"><path fill="#1fa0c6" opacity=".85" d="M0 0 q37.5 -12 75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 V260 H0Z"/></g></g>
                    <g transform="translate(300,568)"><g class="bob">
                        <path d="M0 0 H76 Q66 16 44 16 H22 Q8 12 0 0Z" fill="#b5482e"/>
                        <path d="M38 0 V-62" stroke="#5b3a22" stroke-width="3"/>
                        <path d="M40 -60 L40 -6 L78 -6Z" fill="#fff"/>
                        <path d="M-8 20 H84" stroke="#5b3a22" stroke-width="3" stroke-linecap="round"/>
                        <path d="M14 8 L8 20 M62 10 L70 20" stroke="#5b3a22" stroke-width="2"/>
                    </g></g>
                    <g transform="translate(-40,625)"><g class="wave w3"><path fill="#1388b3" d="M0 0 q37.5 -10 75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 t75 0 V260 H0Z"/></g></g>
                    <path d="M-40 685 Q150 660 320 680 T640 670 V800 H-40Z" fill="#cfa86a"/>
                    <path d="M-40 678 Q150 653 320 673 T640 663 V800 H-40Z" fill="#f1d79c"/>
                    <path class="foam" d="M-40 672 Q150 647 320 667 T640 657" fill="none" stroke="#fff" stroke-width="5" stroke-linecap="round" opacity=".85"/>
                </g>

                <g class="d3">
                    <path d="M-40 745 Q200 722 400 748 T640 738 V800 H-40Z" fill="#e6c88a"/>
                    <use href="#{{ $id }}-palm" x="-35" y="500" width="155" height="248" style="color:#2f9e55"/>
                    <use href="#{{ $id }}-palm" x="480" y="545" width="115" height="184" style="color:#2a8a4c"/>
                    <g transform="translate(405,700)">
                        <path d="M30 40 L24 0" stroke="#5b3a22" stroke-width="3" stroke-linecap="round"/>
                        <path d="M-6 6 Q24 -26 54 6Z" fill="#e4572e"/>
                        <path d="M24 -16 Q10 -12 6 6 H24Z M24 -16 Q38 -12 42 6 H24Z" fill="#fff" opacity=".85"/>
                    </g>
                    <ellipse cx="300" cy="770" rx="14" ry="4" fill="#f7a8b8" opacity=".9"/>
                </g>
            </svg>
        </div>

        {{-- ================= 3. GUNUNG (fajar di Bromo) ================= --}}
        <div class="nsc-scene" data-key="gunung" data-title="{{ $scenes[2]['title'] }}" data-place="{{ $scenes[2]['place'] }}">
            <svg viewBox="0 0 600 800" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                <defs>
                    <linearGradient id="{{ $id }}-skyC" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#241c4d"/><stop offset=".38" stop-color="#7d4686"/>
                        <stop offset=".62" stop-color="#ee8a7c"/><stop offset=".78" stop-color="#ffd0a0"/>
                    </linearGradient>
                    <radialGradient id="{{ $id }}-sunC">
                        <stop offset="0" stop-color="#fff0c4"/><stop offset=".4" stop-color="#ffc58a" stop-opacity=".7"/>
                        <stop offset="1" stop-color="#ff9a7a" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                <rect width="600" height="800" fill="url(#{{ $id }}-skyC)"/>
                @for ($i = 0; $i < 22; $i++)
                    <circle class="twinkle" cx="{{ 20 + ($i * 71) % 570 }}" cy="{{ 20 + ($i * 47) % 260 }}" r="{{ .8 + ($i % 3) * .4 }}" fill="#fff" style="animation-delay:-{{ $i % 5 }}s"/>
                @endfor
                <circle class="sun-glow" cx="300" cy="545" r="200" fill="url(#{{ $id }}-sunC)"/>
                <circle cx="300" cy="545" r="34" fill="#fff0c4"/>
                <use class="cloud slow" href="#{{ $id }}-cloud" x="60" y="380" width="170" height="57" fill="#f6b6a8" opacity=".5"/>
                <use class="cloud" href="#{{ $id }}-cloud" x="380" y="330" width="150" height="50" fill="#f6b6a8" opacity=".45"/>

                <g class="d1">
                    <path d="M-40 575 L80 510 L170 548 L260 490 L340 548 L440 500 L560 548 L640 520 V640 H-40Z" fill="#6a4a86"/>
                    <path d="M375 610 L470 375 L490 373 L585 610Z" fill="#4b3366"/>
                    <ellipse class="smoke" cx="480" cy="372" rx="20" ry="14" fill="#e8d6e8"/>
                    <ellipse class="smoke s2" cx="484" cy="372" rx="18" ry="12" fill="#e8d6e8"/>
                </g>

                <g class="d2">
                    <path d="M25 625 Q110 530 200 625Z" fill="#3a2850"/>
                    <path d="M230 625 L300 530 Q320 520 340 530 L412 625Z" fill="#46305b"/>
                    <path d="M262 592 L292 548 M300 600 L312 540 M340 596 L328 544" stroke="#2e1f40" stroke-width="2" opacity=".7"/>
                    <ellipse class="smoke" cx="320" cy="524" rx="16" ry="11" fill="#f1e2ee"/>
                    <ellipse class="smoke s2" cx="322" cy="524" rx="14" ry="10" fill="#f1e2ee"/>
                    <path d="M-40 618 Q200 596 400 620 T640 606 V800 H-40Z" fill="#5d4563"/>
                    <ellipse class="mist" cx="300" cy="625" rx="340" ry="22" fill="#ffe0d0" opacity=".35"/>
                    <path d="M-40 690 Q200 660 400 695 T640 680 V800 H-40Z" fill="#4a3652"/>
                    <ellipse class="mist m2" cx="300" cy="700" rx="340" ry="18" fill="#ffd8d0" opacity=".25"/>
                </g>

                <g class="d3">
                    <path d="M-40 755 Q250 730 640 760 V800 H-40Z" fill="#2d2038"/>
                    @foreach ([[40, 735, 1.15], [105, 765, .8], [535, 738, 1.25], [585, 768, .85]] as $p)
                        <g transform="translate({{ $p[0] }},{{ $p[1] }}) scale({{ $p[2] }})">
                            <rect x="-3" y="30" width="6" height="16" fill="#0f0b18"/>
                            <path d="M0 -66 L-13 -22 H13Z M0 -38 L-17 6 H17Z M0 -10 L-21 34 H21Z" fill="#150f22"/>
                        </g>
                    @endforeach
                </g>
            </svg>
        </div>

        {{-- ================= 4. MALAM DESA ================= --}}
        <div class="nsc-scene" data-key="malam" data-title="{{ $scenes[3]['title'] }}" data-place="{{ $scenes[3]['place'] }}">
            <svg viewBox="0 0 600 800" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                <defs>
                    <linearGradient id="{{ $id }}-skyD" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0" stop-color="#040a16"/><stop offset=".55" stop-color="#0c2230"/><stop offset=".78" stop-color="#17403f"/>
                    </linearGradient>
                    <radialGradient id="{{ $id }}-moonD">
                        <stop offset="0" stop-color="#fff6d8" stop-opacity=".55"/><stop offset="1" stop-color="#fff6d8" stop-opacity="0"/>
                    </radialGradient>
                </defs>
                <rect width="600" height="800" fill="url(#{{ $id }}-skyD)"/>
                @for ($i = 0; $i < 56; $i++)
                    <circle class="twinkle" cx="{{ 8 + ($i * 97) % 590 }}" cy="{{ 10 + ($i * 53) % 420 }}" r="{{ .7 + ($i % 3) * .5 }}" fill="#fff" style="animation-delay:-{{ $i % 6 }}s;animation-duration:{{ 2 + $i % 4 }}s"/>
                @endfor
                <circle class="sun-glow" cx="440" cy="230" r="130" fill="url(#{{ $id }}-moonD)"/>
                <circle cx="440" cy="230" r="30" fill="#fff6d8"/>
                <circle cx="431" cy="224" r="5" fill="#e8dcb8" opacity=".7"/><circle cx="450" cy="238" r="7" fill="#e8dcb8" opacity=".6"/>

                <g class="d1">
                    <path d="M-40 585 L70 505 L140 545 L240 475 L330 550 L430 500 L540 550 L640 510 V640 H-40Z" fill="#0a1c1c"/>
                    <ellipse class="mist" cx="300" cy="590" rx="330" ry="20" fill="#9fd0d0" opacity=".12"/>
                </g>

                <g class="d2">
                    <path d="M-40 605 Q150 578 300 600 T640 592 V800 H-40Z" fill="#0e2b22"/>
                    <use href="#{{ $id }}-house" x="36"  y="560" width="62" height="46" style="--wall:#1d2c24;--roof:#0f1d17;--roof2:#15281f;--win:#ffcf6b"/>
                    <use href="#{{ $id }}-house" x="118" y="578" width="46" height="34" style="--wall:#1a2820;--roof:#0f1d17;--roof2:#15281f;--win:#ffcf6b"/>
                    <use href="#{{ $id }}-house" x="440" y="556" width="66" height="49" style="--wall:#1d2c24;--roof:#0f1d17;--roof2:#15281f;--win:#ffb84a"/>
                    <path d="M20 566 Q150 604 300 566" fill="none" stroke="#3a2a22" stroke-width="1.2"/>
                    @foreach ([70, 130, 190, 250] as $k => $cx)
                        <circle class="flick" cx="{{ $cx + 20 }}" cy="{{ [586, 594, 592, 580][$k] }}" r="5" fill="#ff5a3c" style="animation-delay:-{{ $k * .6 }}s;filter:drop-shadow(0 0 5px #ff7a4a)"/>
                    @endforeach
                    <path d="M-40 655 Q200 618 380 655 T640 640 V800 H-40Z" fill="#0f3322"/>
                    <path d="M70 652 Q150 636 240 654 Q160 668 70 652Z" fill="#cfe8ff" opacity=".22"/>
                    <path d="M410 648 Q480 634 570 648 Q490 662 410 648Z" fill="#cfe8ff" opacity=".2"/>
                    <path d="M-40 712 Q150 680 320 716 T640 700 V800 H-40Z" fill="#0b2418"/>
                </g>

                <g class="d3">
                    <use href="#{{ $id }}-palm" x="-25" y="525" width="135" height="216" style="color:#050d0a"/>
                    <use href="#{{ $id }}-palm" x="505" y="570" width="95" height="152" style="color:#050d0a"/>
                    @for ($i = 0; $i < 16; $i++)
                        <circle class="ff" cx="{{ 30 + ($i * 37) % 540 }}" cy="{{ 620 + ($i * 29) % 150 }}" r="2.2" fill="#d8ff7a" style="animation-delay:-{{ $i % 6 }}s;animation-duration:{{ 4 + $i % 4 }}s"/>
                    @endfor
                </g>
            </svg>
        </div>
    </div>

    <div class="nsc-scrim"></div>

    <div class="nsc-brand">
        <strong>Jelajah Nusantara</strong>
        <span>Dari sawah, pantai, sampai puncak gunung.</span>
    </div>

    @if ($characters ?? true)
        <div class="nsc-chars">
            @include('auth.partials.nusantara-show', ['variant' => 'idle'])
        </div>
    @endif

    <div class="nsc-ui">
        <div class="nsc-cap" aria-live="polite">
            <div class="nsc-cap-title"></div>
            <div class="nsc-cap-place"></div>
        </div>
        <div class="nsc-chips" role="group" aria-label="Ganti suasana">
            @foreach ($scenes as $s)
                <button type="button" class="nsc-chip" data-key="{{ $s['key'] }}" aria-pressed="false">
                    <span aria-hidden="true">{{ $s['icon'] }}</span>{{ $s['chip'] }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<script>
    (function () {
        var root = document.getElementById('{{ $id }}');
        if (!root) return;

        var scenes = [].slice.call(root.querySelectorAll('.nsc-scene'));
        var chips = [].slice.call(root.querySelectorAll('.nsc-chip'));
        var capTitle = root.querySelector('.nsc-cap-title');
        var capPlace = root.querySelector('.nsc-cap-place');
        var interval = parseInt(root.getAttribute('data-interval'), 10) || 9000;
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var index = 0, timer = null, started = false, walkTimer = null;

        // Saat suasana berganti, turis berjalan di tempat seolah pindah ke destinasi baru
        function travel() {
            var t = root.querySelector('.nsc-chars .nusa-man .tourist');
            var box = root.querySelector('.nsc-chars .nusa-idle-wrapper');
            if (!t || (box && box.classList.contains('has-visitor'))) return;
            t.classList.add('is-walking');
            clearTimeout(walkTimer);
            walkTimer = setTimeout(function () { t.classList.remove('is-walking'); }, 2400);
        }

        root.style.setProperty('--dur', (interval / 1000) + 's');

        function show(n) {
            index = (n + scenes.length) % scenes.length;
            scenes.forEach(function (s, k) { s.classList.toggle('is-active', k === index); });
            chips.forEach(function (c, k) {
                c.classList.toggle('is-active', k === index);
                c.setAttribute('aria-pressed', k === index ? 'true' : 'false');
            });
            capTitle.textContent = scenes[index].getAttribute('data-title');
            capPlace.textContent = scenes[index].getAttribute('data-place');
            var key = scenes[index].getAttribute('data-key');
            root.setAttribute('data-scene', key);
            root.dispatchEvent(new CustomEvent('nusantara:scene', { bubbles: true, detail: { key: key } }));
            if (started) travel();
        }
        function stop() { clearInterval(timer); timer = null; }
        function play() {
            stop();
            if (reduce) return;
            timer = setInterval(function () { show(index + 1); }, interval);
        }

        chips.forEach(function (c, k) {
            c.addEventListener('click', function () {
                show(k); play();
                // turis menyapa saat pengunjung memilih suasana (kalau sedang tidak mengobrol)
                var t = root.querySelector('.nsc-chars .nusa-man .tourist');
                if (t) setTimeout(function () { t.dispatchEvent(new MouseEvent('click', { bubbles: true })); }, 2600);
            });
        });
        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', play);
        document.addEventListener('visibilitychange', function () { document.hidden ? stop() : play(); });

        // parallax halus mengikuti kursor (hanya perangkat dengan mouse)
        if (!reduce && window.matchMedia('(hover: hover)').matches) {
            window.addEventListener('mousemove', function (e) {
                root.style.setProperty('--mx', ((e.clientX / window.innerWidth) - .5) * 2);
                root.style.setProperty('--my', ((e.clientY / window.innerHeight) - .5) * 2);
            }, { passive: true });
        }

        var startKey = root.getAttribute('data-start');
        var startIdx = scenes.findIndex(function (s) { return s.getAttribute('data-key') === startKey; });
        show(startIdx > -1 ? startIdx : 0);
        started = true;
        play();
    })();
</script>

<script>
    // Pakaian karakter berganti otomatis mengikuti tempat (sawah, pantai, gunung, malam).
    // Warna diatur lewat CSS; bentuk tambahan (topi, syal, sarung, dll.) disuntik ke SVG di sini.
    (function () {
        var root = document.getElementById('{{ $id }}');
        if (!root) return;
        var NS = 'http://www.w3.org/2000/svg';

        function nodes(html) {
            var g = document.createElementNS(NS, 'g');
            g.innerHTML = html;
            return Array.prototype.slice.call(g.childNodes);
        }
        function put(parent, html, before) {
            if (!parent) return;
            nodes(html).forEach(function (n) { before ? parent.insertBefore(n, before) : parent.appendChild(n); });
        }
        function skinOf(svg) {
            var g = svg.querySelector('linearGradient[id^="skinGrad"], linearGradient[id^="fSkin"]');
            return g ? 'url(#' + g.id + ')' : '#e8b78f';
        }
        function flowers() {
            var pts = [[84,125],[112,134],[92,150],[118,160],[80,172],[104,178],[98,118]], out = '';
            pts.forEach(function (p) {
                out += '<circle cx="' + p[0] + '" cy="' + p[1] + '" r="3.8" fill="#fff" opacity=".92"/>' +
                       '<circle cx="' + p[0] + '" cy="' + p[1] + '" r="1.4" fill="#ffd166"/>';
            });
            return out;
        }

        function dress(svg) {
            if (svg.getAttribute('data-outfit')) return;
            svg.setAttribute('data-outfit', '1');

            var isFriend = svg.classList.contains('friend');
            var skin = skinOf(svg);
            var plaid = 'pl' + Math.random().toString(36).slice(2, 8);
            var body = svg.querySelector('.t-body');
            var armL = svg.querySelector('.t-arm-l');
            var hat  = svg.querySelector('.t-hat');

            // tandai bentuk topi bawaan agar bisa disembunyikan saat topi lain dipakai
            if (hat) Array.prototype.slice.call(hat.children).forEach(function (c) { c.classList.add('base-hat'); });

            // celana pendek (pantai): kulit menutupi bagian bawah kaki, ikut bergerak saat berjalan
            [['.t-foot-l', 'M81.3 214 Q81.5 235 82 252 L92 252 Q92 235 92.6 214Z'],
             ['.t-foot-r', 'M118.7 214 Q118.5 235 118 252 L108 252 Q108 235 107.4 214Z']].forEach(function (l) {
                put(svg.querySelector(l[0]), '<path class="ov ov-pantai" fill="' + skin + '" d="' + l[1] + '"/>');
            });

            // motif kotak-kotak untuk sarung
            put(svg, '<defs><pattern id="' + plaid + '" width="8" height="8" patternUnits="userSpaceOnUse">' +
                '<rect width="8" height="8" fill="#8b2e2e"/><path d="M0 4H8M4 0V8" stroke="#1f1f1f" stroke-width="1.4"/>' +
                '<path d="M0 .4H8M.4 0V8" stroke="#e9c46a" stroke-width=".8"/></pattern></defs>', svg.firstChild);

            if (!isFriend) {
                /* ===== TURIS ===== */
                // sawah: caping
                put(hat, '<g class="ov ov-sawah"><path d="M100 6 L142 50 Q100 62 58 50Z" fill="#e0b95c"/>' +
                    '<path d="M100 6 L84 55 M100 6 L100 58 M100 6 L116 55 M100 6 L71 52 M100 6 L129 52" stroke="#b58a35" stroke-width="1.2" fill="none"/>' +
                    '<path d="M58 50 Q100 62 142 50" fill="none" stroke="#a97d2c" stroke-width="2.5"/></g>');
                // gunung: kupluk
                put(hat, '<g class="ov ov-gunung"><path d="M78 52 Q78 22 100 22 Q122 22 122 52 Q100 58 78 52Z" fill="#2b3a67"/>' +
                    '<path d="M77 46 Q100 53 123 46 L123 55 Q100 62 77 55Z" fill="#1b2547"/>' +
                    '<circle cx="100" cy="20" r="7" fill="#f4e3d3"/></g>');
                // pantai: kemeja bunga + kalung bunga
                put(body, '<g class="ov ov-pantai">' + flowers() +
                    '<path d="M80 101 Q100 138 120 101" fill="none" stroke="#ff6f91" stroke-width="6" stroke-dasharray="0 8" stroke-linecap="round"/>' +
                    '<path d="M80 101 Q100 138 120 101" fill="none" stroke="#ffd166" stroke-width="6" stroke-dasharray="0 8" stroke-dashoffset="4" stroke-linecap="round"/></g>', armL);
                // gunung: jaket tebal + syal
                put(body, '<g class="ov ov-gunung"><path d="M73 128 H127 M72 148 H128 M72 168 H127" stroke="#00000033" stroke-width="1.6" fill="none"/>' +
                    '<path d="M100 114 V190" stroke="#f3d9a4" stroke-width="1.6"/>' +
                    '<path d="M82 98 Q100 112 118 98 L120 108 Q100 124 80 108Z" fill="#2b3a67"/>' +
                    '<path d="M108 114 L119 112 L121 152 L110 154Z" fill="#2b3a67"/>' +
                    '<path d="M109 124 L120 122 M109 134 L120 132 M110 144 L121 142" stroke="#f4e3d3" stroke-width="1.6"/></g>', armL);
                // malam: jaket dengan pita reflektif
                put(body, '<g class="ov ov-malam"><path d="M72 164 Q100 171 128 164" fill="none" stroke="#d8ecff" stroke-width="3" opacity=".75"/>' +
                    '<path d="M100 114 V190" stroke="#0e1a30" stroke-width="1.6"/>' +
                    '<path d="M84 100 L100 118 L116 100" fill="none" stroke="#0e1a30" stroke-width="3"/></g>', armL);
                // malam: lampu lentera di tangan kiri
                put(armL, '<g class="ov ov-malam"><circle class="lantern-glow" cx="70" cy="207" r="26" fill="#ffd27a" opacity=".35"/>' +
                    '<path d="M70 184 V196" stroke="#cbd5e0" stroke-width="1.5"/>' +
                    '<rect x="62" y="196" width="16" height="22" rx="4" fill="#ffb347" stroke="#7a4a12" stroke-width="1.5"/></g>');
            } else {
                /* ===== TEMAN LOKAL ===== */
                // pantai: topi pandan
                put(hat, '<g class="ov ov-pantai"><ellipse cx="100" cy="49" rx="42" ry="10" fill="#e8c96f"/>' +
                    '<path d="M82 49 Q82 26 100 26 Q118 26 118 49 Q100 56 82 49Z" fill="#f2d98a"/>' +
                    '<path d="M82 45 Q100 52 118 45 L118 50 Q100 57 82 50Z" fill="#c0392b"/></g>');
                // gunung: jaket + sarung selempang
                put(body, '<g class="ov ov-gunung"><path d="M100 114 V190" stroke="#d8d2b8" stroke-width="1.6"/>' +
                    '<path d="M82 99 Q100 112 118 99 L118 106 Q100 120 82 106Z" fill="#3d5a40"/>' +
                    '<path d="M73 103 L85 99 L127 170 L115 178Z" fill="url(#' + plaid + ')" stroke="#1f1f1f" stroke-width="1"/></g>', armL);
                // malam: baju koko + sarung di pinggang
                put(body, '<g class="ov ov-malam"><path d="M100 108 V150" stroke="#b9b09a" stroke-width="1.5"/>' +
                    '<circle cx="100" cy="116" r="1.5" fill="#8a8068"/><circle cx="100" cy="128" r="1.5" fill="#8a8068"/><circle cx="100" cy="140" r="1.5" fill="#8a8068"/>' +
                    '<path d="M74 180 Q100 190 126 180 L131 236 Q100 248 69 236Z" fill="url(#' + plaid + ')" stroke="#1f1f1f" stroke-width="1"/>' +
                    '<path d="M74 180 Q100 190 126 180 L126 186 Q100 196 74 186Z" fill="#2b1d16"/></g>', armL);
            }
        }

        Array.prototype.slice.call(root.querySelectorAll('.nsc-chars .tourist')).forEach(dress);
    })();
</script>