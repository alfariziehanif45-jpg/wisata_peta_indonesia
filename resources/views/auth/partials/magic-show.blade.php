{{--
    Partial pertunjukan sulap.
    Parameter:
      $id       : id elemen overlay (contoh: 'regIntro')
      $variant  : 'open'  -> tirai terbuka, pesulap menyapa, lalu halaman muncul
                  'close' -> pesulap pamit, membungkuk, tirai menutup (diputar lewat JS)
                  'error' -> pesulap sedih & memberitahu login salah
                             (otomatis jika $autoplay = true, atau lewat JS: el.magicPlay())
      $line     : teks ucapan pesulap
      $sub      : (opsional) teks penjelasan kecil di bawah ucapan (varian 'error')
      $autoplay : (opsional) putar langsung saat halaman dimuat (varian 'error')
--}}
@php
    $autoplay = $autoplay ?? false;
    $sub = $sub ?? null;
@endphp

@once
<style>
    /* ===== Panggung ===== */
    .show-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: grid; place-items: center; overflow: hidden;
        background:
            radial-gradient(ellipse at 50% 38%, #4a2f8a66 0%, transparent 62%),
            linear-gradient(180deg, #120d2b 0%, #2a1a4f 100%);
    }
    .show-overlay.is-skipped { display: none !important; }
    .show-stage {
        position: relative; z-index: 1;
        width: min(420px, 92vw); height: min(380px, 70vh);
        display: flex; align-items: flex-end; justify-content: center;
    }
    .show-floor {
        position: absolute; left: -50vw; right: -50vw; bottom: 22px; height: 2px;
        background: linear-gradient(90deg, transparent, #f2c14e66, transparent);
    }
    .show-line {
        position: absolute; z-index: 3; left: 0; right: 0; top: 14%;
        margin: 0; padding: 0 16px; text-align: center;
        font: 600 clamp(1.15rem, 4.5vw, 1.6rem)/1.25 Georgia, 'Times New Roman', serif;
        letter-spacing: .03em; color: #f2c14e;
        text-shadow: 0 2px 12px #000a;
        opacity: 0;
    }
    .show-sub { display: none; }
    .show-skip {
        position: absolute; z-index: 5; top: 16px; right: 16px;
        padding: 8px 14px; border: 1px solid #ffffff55; border-radius: 999px;
        background: #ffffff14; color: #fff; cursor: pointer;
        font-family: inherit; font-size: .85rem; font-weight: 500; line-height: 1;
    }
    .show-skip:hover { background: #ffffff2b; }
    .show-skip:focus-visible { outline: 2px solid #f2c14e; outline-offset: 2px; }

    /* ===== Tirai ===== */
    .show-curtain {
        position: absolute; z-index: 2; top: 0; bottom: 0; width: 50.5%;
        background: repeating-linear-gradient(90deg, #7a1020 0 18px, #a31a2e 18px 34px, #6a0d1b 34px 52px);
        border-bottom: 6px solid #f2c14e;
    }
    .show-curtain-l { left: 0;  box-shadow: inset -16px 0 26px #0007; }
    .show-curtain-r { right: 0; box-shadow: inset 16px 0 26px #0007; }

    /* Tirai atas (valance) berumbai emas */
    .show-valance {
        position: absolute; z-index: 3; top: 0; left: 0; right: 0; height: 52px;
        background: linear-gradient(180deg, #8f1528, #6a0d1b);
        border-bottom: 5px solid #f2c14e;
        box-shadow: 0 8px 22px #0009;
    }
    .show-valance::after {
        content: ''; position: absolute; left: 0; right: 0; top: 100%; height: 10px;
        background: radial-gradient(circle at 10px 0, #f2c14e 5px, transparent 6px) 0 0 / 20px 10px repeat-x;
    }

    /* ===== Pesulap ===== */
    .show-man { width: min(230px, 54vw); margin-bottom: 14px; }
    .magician { width: 100%; height: auto; overflow: visible; }
    .magician * { transform-box: fill-box; }
    .magician .m-torso { transform-box: view-box; transform-origin: 100px 196px; }
    .magician .m-head  { transform-box: view-box; transform-origin: 100px 90px; }
    .magician .m-arm-r { transform-box: view-box; transform-origin: 135px 106px; }
    .magician .m-hat   { transform-origin: 50% 50%; }
    .magician .m-eyes  { transform-origin: 50% 50%; animation: blink 4.5s infinite; }
    .magician .m-talk  { opacity: 0; transform-origin: 50% 50%; }
    .magician .m-spark, .magician .m-puff { opacity: 0; transform-origin: 50% 50%; }
    /* ekspresi sedih: tersembunyi secara default */
    .magician .m-brows-sad, .magician .m-frown, .magician .m-sweat, .magician .m-q { opacity: 0; }
    .magician .m-sweat { transform-origin: 50% 50%; }
    .magician .m-q { transform-origin: 50% 50%; }

    /* ===== Varian: BUKA pertunjukan (register) ===== */
    .show-open {
        clip-path: circle(150% at 50% 55%);
        animation: showReveal 1.1s cubic-bezier(.65, 0, .35, 1) 4s forwards;
    }
    .show-open .show-curtain-l { animation: curtainL 1.3s cubic-bezier(.6, 0, .3, 1) .4s forwards; }
    .show-open .show-curtain-r { animation: curtainR 1.3s cubic-bezier(.6, 0, .3, 1) .4s forwards; }
    .show-open .show-line { animation: lineIn .6s ease-out 2s forwards; }
    .show-open .m-hat     { animation: hatTip 1s ease-in-out 2s both; }
    .show-open .m-head    { animation: nod 1.6s ease-in-out 2s 1; }
    .show-open .m-talk    { animation: talk .22s ease-in-out 2.1s 8 alternate; }
    .show-open .m-arm-r   { animation: armUp .5s ease-out 1.8s forwards, wand .25s ease-in-out 2.6s 4 alternate; }
    .show-open .m-spark   { animation: spark .9s ease-out 3s both; }
    .show-open .m-puff    { animation: puff .8s ease-out 3s both; }

    /* ===== Varian: TUTUP pertunjukan (login berhasil) ===== */
    .show-close { display: none; }
    .show-close .show-curtain-l { transform: translateX(-101%); }
    .show-close .show-curtain-r { transform: translateX(101%); }
    .show-close.is-playing { display: grid; animation: fadeIn .35s ease-out; }
    .show-close.is-playing .show-curtain-l { animation: closeL 1s cubic-bezier(.6, 0, .3, 1) 2.4s forwards; }
    .show-close.is-playing .show-curtain-r { animation: closeR 1s cubic-bezier(.6, 0, .3, 1) 2.4s forwards; }
    .show-close.is-playing .show-line { animation: lineIn .6s ease-out .5s forwards; }
    .show-close.is-playing .m-talk    { animation: talk .22s ease-in-out .6s 8 alternate; }
    .show-close.is-playing .m-arm-r   { animation: armUp .4s ease-out .3s forwards, wand .25s ease-in-out .8s 4 alternate; }
    .show-close.is-playing .m-spark   { animation: spark .9s ease-out 1.1s both; }
    .show-close.is-playing .m-puff    { animation: puff .8s ease-out 1.1s both; }
    .show-close.is-playing .m-torso   { animation: bow 1s ease-in-out 1.6s both; }

    /* ===== Varian: ERROR (email / kata sandi salah) ===== */
    .show-error {
        display: none;
        background:
            radial-gradient(ellipse at 50% 42%, #a3203866 0%, transparent 62%),
            linear-gradient(180deg, #120d2bf2 0%, #2a1a4ff2 100%);
        transition: opacity .35s ease;
    }
    .show-error.is-playing { display: grid; animation: fadeIn .35s ease-out; }
    .show-error.is-leaving { opacity: 0; }
    .show-error .show-curtain,
    .show-error .show-valance { display: none; }

    /* gelembung ucapan */
    .show-error .show-line {
        top: auto; bottom: calc(50% + min(380px, 70vh) / 2 - 14px);
        width: fit-content; max-width: min(400px, 90vw); margin: 0 auto; padding: 14px 20px;
        background: #fff3d4; color: #5c0b1a; text-shadow: none;
        border: 2px solid #c0243c; border-radius: 16px;
        box-shadow: 0 10px 30px #000a;
        font-size: clamp(1rem, 4vw, 1.3rem);
        transform-origin: 50% 100%;
    }
    .show-error .show-line::after {
        content: ''; position: absolute; left: 50%; bottom: -10px; margin-left: -10px;
        border: 10px solid transparent; border-top-color: #c0243c; border-bottom: 0;
    }
    .show-error .show-sub {
        display: block; margin-top: 6px;
        font: 400 .85rem/1.4 'Trebuchet MS', 'Segoe UI', sans-serif; letter-spacing: 0; color: #7a2b1a;
    }
    /* tombol "Coba lagi" */
    .show-error .show-skip {
        top: auto; right: auto; bottom: 8%; left: 50%; transform: translateX(-50%);
        padding: 12px 28px; border: 0; background: #f2c14e; color: #2a1a05;
        font-weight: 700; font-size: .95rem; box-shadow: 0 4px 0 #8a6414, 0 10px 22px #0007;
    }
    .show-error .show-skip:hover { background: #ffd978; }

    /* urutan animasi ekspresi */
    .show-error.is-playing .show-line { animation: bubbleIn .5s cubic-bezier(.2, 1.4, .4, 1) .7s forwards; }
    .show-error.is-playing .m-brows-ok,
    .show-error.is-playing .m-smile   { animation: popOut .01s linear .5s forwards; }
    .show-error.is-playing .m-brows-sad,
    .show-error.is-playing .m-frown   { animation: popIn .01s linear .5s forwards; }
    .show-error.is-playing .m-head    { animation: shakeNo 1.1s ease-in-out .7s 1; }
    .show-error.is-playing .m-hat     { animation: hatDroop .6s ease-out .6s forwards; }
    .show-error.is-playing .m-pupils  { animation: glance 1.2s ease-in-out .9s 2; }
    .show-error.is-playing .m-talk    { animation: talk .24s ease-in-out .8s 6 alternate; }
    .show-error.is-playing .m-sweat   { animation: sweat 1.2s ease-in .9s both; }
    .show-error.is-playing .m-q       { animation: qPop .6s cubic-bezier(.2, 1.5, .4, 1) .8s both; }
    .show-error.is-playing .m-torso   { animation: slump .8s ease-out .6s forwards; }

    /* ===== Keyframes ===== */
    @keyframes showReveal { from { clip-path: circle(150% at 50% 55%); } to { clip-path: circle(0% at 50% 45%); } }
    @keyframes fadeIn     { from { opacity: 0; } to { opacity: 1; } }
    @keyframes lineIn     { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes bubbleIn   { from { opacity: 0; transform: scale(.7) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes popIn      { to { opacity: 1; } }
    @keyframes popOut     { to { opacity: 0; } }
    @keyframes curtainL   { to { transform: translateX(-101%); } }
    @keyframes curtainR   { to { transform: translateX(101%); } }
    @keyframes closeL     { from { transform: translateX(-101%); } to { transform: translateX(0); } }
    @keyframes closeR     { from { transform: translateX(101%); }  to { transform: translateX(0); } }
    @keyframes armUp      { from { transform: rotate(0); } to { transform: rotate(-130deg); } }
    @keyframes wand       { from { transform: rotate(-130deg); } to { transform: rotate(-108deg); } }
    @keyframes blink {
        0%, 93%, 100% { transform: scaleY(1); }
        96%           { transform: scaleY(.08); }
    }
    @keyframes talk {
        from { opacity: 1; transform: scaleY(.25); }
        to   { opacity: 1; transform: scaleY(1); }
    }
    @keyframes nod {
        0%, 100% { transform: rotate(0); }
        25%      { transform: rotate(4deg); }
        60%      { transform: rotate(-3deg); }
    }
    @keyframes shakeNo {
        0%, 100% { transform: rotate(0); }
        15% { transform: rotate(-9deg); }
        35% { transform: rotate(8deg); }
        55% { transform: rotate(-7deg); }
        75% { transform: rotate(5deg); }
    }
    @keyframes hatDroop {
        to { transform: translate(5px, 4px) rotate(11deg); }
    }
    @keyframes slump {
        to { transform: translateY(4px) rotate(-2deg); }
    }
    @keyframes glance {
        0%, 100% { transform: translateX(0); }
        30% { transform: translateX(-2px); }
        70% { transform: translateX(2px); }
    }
    @keyframes sweat {
        0%   { opacity: 0; transform: translateY(-4px) scale(.6); }
        25%  { opacity: 1; transform: translateY(0) scale(1); }
        100% { opacity: 1; transform: translateY(14px) scale(1); }
    }
    @keyframes qPop {
        0%   { opacity: 0; transform: scale(0) rotate(-30deg); }
        70%  { opacity: 1; transform: scale(1.2) rotate(8deg); }
        100% { opacity: 1; transform: scale(1) rotate(0); }
    }
    @keyframes hatTip {
        0%, 100% { transform: translate(0, 0) rotate(0); }
        40%, 70% { transform: translate(-12px, -26px) rotate(-22deg); }
    }
    @keyframes bow {
        0%, 100% { transform: rotate(0) translateY(0); }
        50%      { transform: rotate(22deg) translateY(8px); }
    }
    @keyframes spark {
        0%   { opacity: 0; transform: translate(0, 0) scale(.3); }
        25%  { opacity: 1; }
        100% { opacity: 0; transform: translate(var(--x), var(--y)) scale(1.2); }
    }
    @keyframes puff { 0% { opacity: .9; transform: scale(.4); } 100% { opacity: 0; transform: scale(4.5); } }

    @media (prefers-reduced-motion: reduce) {
        .show-open { display: none !important; }
        .magician .m-eyes { animation: none; }
        /* pesan error tetap tampil, tanpa gerakan */
        .show-error, .show-error * { animation: none !important; }
        .show-error .show-line { opacity: 1; }
        .show-error .m-brows-ok, .show-error .m-smile { opacity: 0; }
        .show-error .m-brows-sad, .show-error .m-frown,
        .show-error .m-sweat, .show-error .m-q { opacity: 1; }
    }
</style>
@endonce

<div class="show-overlay show-{{ $variant }} {{ $autoplay ? 'is-playing' : '' }}" id="{{ $id }}" role="{{ $variant === 'error' ? 'alert' : 'presentation' }}">
    <button type="button" class="show-skip" id="{{ $id }}Skip" aria-label="{{ $variant === 'error' ? 'Tutup pesan dan coba lagi' : 'Lewati animasi' }}">{{ $variant === 'error' ? 'Coba lagi' : 'Lewati' }}</button>

    <div class="show-curtain show-curtain-l"></div>
    <div class="show-curtain show-curtain-r"></div>
    <div class="show-valance"></div>

    <p class="show-line">{{ $line }}@if ($sub)<span class="show-sub">{{ $sub }}</span>@endif</p>

    <div class="show-stage">
        <div class="show-floor"></div>
        <div class="show-man">
            <svg class="magician" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <ellipse cx="100" cy="288" rx="54" ry="7" fill="#000" opacity=".35"/>

                <g class="m-torso">
                    <!-- jubah (belakang) -->
                    <path d="M72 100 C38 150 34 205 44 238 L156 238 C166 205 162 150 128 100 Z" fill="#5b2fb8"/>
                    <path d="M78 104 C52 150 50 200 58 232 L142 232 C150 200 148 150 122 104 Z" fill="#a31a2e"/>

                    <!-- lengan kiri (menggantung) -->
                    <rect x="58" y="102" width="14" height="64" rx="7" fill="#1b1330"/>
                    <rect x="58" y="159" width="14" height="7" rx="2" fill="#fff"/>
                    <circle cx="65" cy="172" r="7" fill="#f4cfa9"/>

                    <!-- jas -->
                    <path d="M70 106 Q72 94 90 92 L110 92 Q128 94 130 106 L134 200 H66 Z" fill="#1b1330"/>
                    <!-- kemeja -->
                    <path d="M90 94 L100 152 L110 94 Z" fill="#fff"/>
                    <!-- kerah jas -->
                    <path d="M88 94 L100 152 L80 124 Z" fill="#2b2050"/>
                    <path d="M112 94 L100 152 L120 124 Z" fill="#2b2050"/>
                    <!-- kancing -->
                    <circle cx="100" cy="164" r="2" fill="#f2c14e"/>
                    <circle cx="100" cy="180" r="2" fill="#f2c14e"/>
                    <!-- dasi kupu-kupu -->
                    <path d="M100 104 L89 98 L89 110 Z" fill="#c0243c"/>
                    <path d="M100 104 L111 98 L111 110 Z" fill="#c0243c"/>
                    <circle cx="100" cy="104" r="3" fill="#8f1528"/>

                    <!-- leher -->
                    <rect x="93" y="86" width="14" height="12" rx="4" fill="#e0b08c"/>

                    <!-- kepala -->
                    <g class="m-head">
                        <!-- telinga -->
                        <circle cx="81" cy="70" r="4.2" fill="#eec19b"/>
                        <circle cx="119" cy="70" r="4.2" fill="#eec19b"/>
                        <!-- wajah -->
                        <ellipse cx="100" cy="68" rx="19" ry="22" fill="#f4cfa9"/>
                        <!-- rambut -->
                        <path d="M81 64 Q80 46 100 46 Q120 46 119 64 Q113 53 100 53 Q87 53 81 64 Z" fill="#3a2418"/>
                        <path d="M81 62 Q79 72 82 78 L84 64 Z" fill="#3a2418"/>
                        <path d="M119 62 Q121 72 118 78 L116 64 Z" fill="#3a2418"/>
                        <!-- pipi -->
                        <circle cx="88" cy="77" r="4" fill="#ff8f8f" opacity=".3"/>
                        <circle cx="112" cy="77" r="4" fill="#ff8f8f" opacity=".3"/>
                        <!-- alis (ramah) -->
                        <g class="m-brows-ok">
                            <path d="M86 61 Q92 57.5 97 60" stroke="#3a2418" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                            <path d="M103 60 Q108 57.5 114 61" stroke="#3a2418" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        </g>
                        <!-- alis (khawatir: ujung dalam naik) -->
                        <g class="m-brows-sad">
                            <path d="M85 62 Q91 60 97 55.5" stroke="#3a2418" stroke-width="1.9" fill="none" stroke-linecap="round"/>
                            <path d="M103 55.5 Q109 60 115 62" stroke="#3a2418" stroke-width="1.9" fill="none" stroke-linecap="round"/>
                        </g>
                        <!-- mata -->
                        <g class="m-eyes">
                            <ellipse cx="92" cy="67.5" rx="4" ry="3" fill="#fff"/>
                            <ellipse cx="108" cy="67.5" rx="4" ry="3" fill="#fff"/>
                            <g class="m-pupils">
                                <circle cx="92.6" cy="67.8" r="2" fill="#2a1a14"/>
                                <circle cx="108.6" cy="67.8" r="2" fill="#2a1a14"/>
                                <circle cx="93.2" cy="67" r=".7" fill="#fff"/>
                                <circle cx="109.2" cy="67" r=".7" fill="#fff"/>
                            </g>
                        </g>
                        <!-- hidung -->
                        <path d="M100 70 Q103.5 76 99 77" stroke="#d29a74" stroke-width="1.6" fill="none" stroke-linecap="round"/>
                        <!-- senyum -->
                        <path class="m-smile" d="M93 84 Q100 90 107 84" stroke="#8a3a2a" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        <!-- cemberut -->
                        <path class="m-frown" d="M93.5 89 Q100 83 106.5 89" stroke="#8a3a2a" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        <!-- mulut terbuka saat bicara -->
                        <ellipse class="m-talk" cx="100" cy="85.5" rx="4.5" ry="3.2" fill="#5a1a1a"/>
                        <!-- kumis -->
                        <path d="M89 81 Q95 76 100 79.5 Q105 76 111 81 Q105 83.5 100 81.5 Q95 83.5 89 81 Z" fill="#3a2418"/>
                        <!-- keringat dingin -->
                        <path class="m-sweat" d="M122 52 q-4.5 6.5 0 9.5 q4.5 -3 0 -9.5 z" fill="#8fd3ff" stroke="#5fb4ea" stroke-width=".8"/>

                        <!-- topi -->
                        <g class="m-hat">
                            <rect x="78" y="8" width="44" height="42" rx="4" fill="#120d22"/>
                            <rect x="78" y="36" width="44" height="9" fill="#c0243c"/>
                            <rect x="78" y="43" width="44" height="2" fill="#f2c14e"/>
                            <ellipse cx="100" cy="50" rx="30" ry="6" fill="#120d22"/>
                        </g>
                    </g>

                    <!-- tanda tanya -->
                    <text class="m-q" x="138" y="36" font-family="Georgia, serif" font-size="30" font-weight="700" fill="#f2c14e" stroke="#120d22" stroke-width="1">?</text>

                    <!-- lengan kanan + tongkat -->
                    <g class="m-arm-r">
                        <rect x="128" y="102" width="14" height="64" rx="7" fill="#1b1330"/>
                        <rect x="128" y="159" width="14" height="7" rx="2" fill="#fff"/>
                        <rect x="132.5" y="164" width="5" height="52" rx="2" fill="#fff"/>
                        <rect x="132.5" y="200" width="5" height="16" rx="2" fill="#1b1330"/>
                        <circle cx="135" cy="172" r="7" fill="#f4cfa9"/>
                        <path d="M130 172 H140" stroke="#d29a74" stroke-width="1" stroke-linecap="round"/>
                    </g>
                </g>

                <!-- kaki -->
                <rect x="84" y="196" width="13" height="74" rx="5" fill="#1b1330"/>
                <rect x="103" y="196" width="13" height="74" rx="5" fill="#1b1330"/>
                <ellipse cx="88" cy="273" rx="12" ry="6" fill="#0c0818"/>
                <ellipse cx="112" cy="273" rx="12" ry="6" fill="#0c0818"/>

                <!-- kilau & asap di ujung tongkat -->
                <g transform="translate(222 56)">
                    <path class="m-spark" style="--x:-30px;--y:-46px" d="M0 -9 l3 6 6 3 -6 3 -3 6 -3 -6 -6 -3 6 -3z" fill="#f2c14e"/>
                    <path class="m-spark" style="--x:34px;--y:-36px"  d="M0 -9 l3 6 6 3 -6 3 -3 6 -3 -6 -6 -3 6 -3z" fill="#fff"/>
                    <path class="m-spark" style="--x:48px;--y:6px"    d="M0 -7 l2 5 5 2 -5 2 -2 5 -2 -5 -5 -2 5 -2z" fill="#b794ff"/>
                    <path class="m-spark" style="--x:-44px;--y:-6px"  d="M0 -7 l2 5 5 2 -5 2 -2 5 -2 -5 -5 -2 5 -2z" fill="#f2c14e"/>
                    <path class="m-spark" style="--x:6px;--y:-62px"   d="M0 -9 l3 6 6 3 -6 3 -3 6 -3 -6 -6 -3 6 -3z" fill="#fff"/>
                    <circle class="m-puff" cx="0" cy="0" r="10" fill="#e9e1ff"/>
                </g>
            </svg>
        </div>
    </div>
</div>

@if ($variant === 'open')
<script>
    (function () {
        var overlay = document.getElementById('{{ $id }}');
        var skip = document.getElementById('{{ $id }}Skip');
        if (!overlay) return;
        function closeShow() { overlay.classList.add('is-skipped'); }
        skip.addEventListener('click', closeShow);
        overlay.addEventListener('animationend', function (e) {
            if (e.animationName === 'showReveal') closeShow();
        });
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

        // Tutup overlay; fokuskan kembali ke kolom kata sandi
        function hide() {
            clearTimeout(hideTimer);
            overlay.classList.add('is-leaving');
            leaveTimer = setTimeout(function () {
                overlay.classList.remove('is-playing', 'is-leaving');
                var pw = document.querySelector('input[type="password"]');
                if (pw) { pw.focus(); pw.select(); }
            }, 350);
        }

        // Putar (ulang) ekspresi pesulap: panggil lewat overlay.magicPlay()
        overlay.magicPlay = function () {
            clearTimeout(hideTimer);
            clearTimeout(leaveTimer);
            overlay.classList.remove('is-playing', 'is-leaving');
            void overlay.offsetWidth; // reset animasi
            overlay.classList.add('is-playing');
            hideTimer = setTimeout(hide, 6500);
            btn.focus();
        };

        btn.addEventListener('click', hide);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-playing')) hide();
        });

        @if ($autoplay)
        overlay.magicPlay();
        @endif
    })();
</script>
@endif