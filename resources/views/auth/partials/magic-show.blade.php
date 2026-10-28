{{--
    Partial pertunjukan sulap.
    Parameter:
      $id      : id elemen overlay (contoh: 'regIntro')
      $variant : 'open'  -> tirai terbuka, pesulap menyapa, lalu halaman muncul
                 'close' -> pesulap pamit, membungkuk, tirai menutup (diputar lewat JS)
      $line    : teks ucapan pesulap
--}}
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
        position: absolute; z-index: 3; left: 0; right: 0; top: 12%;
        margin: 0; padding: 0 16px; text-align: center;
        font: 600 clamp(1.15rem, 4.5vw, 1.6rem)/1.25 Georgia, 'Times New Roman', serif;
        letter-spacing: .03em; color: #f2c14e;
        text-shadow: 0 2px 12px #000a;
        opacity: 0;
    }
    .show-skip {
        position: absolute; z-index: 4; top: 16px; right: 16px;
        padding: 8px 14px; border: 1px solid #ffffff55; border-radius: 999px;
        background: #ffffff14; color: #fff; font: 500 .85rem/1 inherit; cursor: pointer;
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

    /* ===== Pesulap ===== */
    .show-man { width: min(220px, 52vw); margin-bottom: 14px; }
    .magician { width: 100%; height: auto; overflow: visible; }
    .magician * { transform-box: fill-box; }
    .magician .m-torso { transform-box: view-box; transform-origin: 100px 196px; }
    .magician .m-arm-r { transform-box: view-box; transform-origin: 131px 104px; }
    .magician .m-hat   { transform-origin: 50% 50%; }
    .magician .m-spark, .magician .m-puff { opacity: 0; transform-origin: 50% 50%; }

    /* ===== Varian: BUKA pertunjukan (register) ===== */
    .show-open {
        clip-path: circle(150% at 50% 55%);
        animation: showReveal 1.1s cubic-bezier(.65, 0, .35, 1) 4s forwards;
    }
    .show-open .show-curtain-l { animation: curtainL 1.3s cubic-bezier(.6, 0, .3, 1) .4s forwards; }
    .show-open .show-curtain-r { animation: curtainR 1.3s cubic-bezier(.6, 0, .3, 1) .4s forwards; }
    .show-open .show-line { animation: lineIn .6s ease-out 2s forwards; }
    .show-open .m-hat     { animation: hatTip 1s ease-in-out 2s both; }
    .show-open .m-arm-r   { animation: armUp .5s ease-out 1.8s forwards, wand .25s ease-in-out 2.6s 4 alternate; }
    .show-open .m-spark   { animation: spark .9s ease-out 3s both; }
    .show-open .m-puff    { animation: puff .8s ease-out 3s both; }

    /* ===== Varian: TUTUP pertunjukan (login) ===== */
    .show-close { display: none; }
    .show-close .show-curtain-l { transform: translateX(-101%); }
    .show-close .show-curtain-r { transform: translateX(101%); }
    .show-close.is-playing { display: grid; animation: fadeIn .35s ease-out; }
    .show-close.is-playing .show-curtain-l { animation: closeL 1s cubic-bezier(.6, 0, .3, 1) 2.4s forwards; }
    .show-close.is-playing .show-curtain-r { animation: closeR 1s cubic-bezier(.6, 0, .3, 1) 2.4s forwards; }
    .show-close.is-playing .show-line { animation: lineIn .6s ease-out .5s forwards; }
    .show-close.is-playing .m-arm-r   { animation: armUp .4s ease-out .3s forwards, wand .25s ease-in-out .8s 4 alternate; }
    .show-close.is-playing .m-torso   { animation: bow 1s ease-in-out 1.6s both; }

    /* ===== Keyframes ===== */
    @keyframes showReveal { from { clip-path: circle(150% at 50% 55%); } to { clip-path: circle(0% at 50% 45%); } }
    @keyframes fadeIn     { from { opacity: 0; } to { opacity: 1; } }
    @keyframes lineIn     { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes curtainL   { to { transform: translateX(-101%); } }
    @keyframes curtainR   { to { transform: translateX(101%); } }
    @keyframes closeL     { from { transform: translateX(-101%); } to { transform: translateX(0); } }
    @keyframes closeR     { from { transform: translateX(101%); }  to { transform: translateX(0); } }
    @keyframes armUp      { from { transform: rotate(0); } to { transform: rotate(-140deg); } }
    @keyframes wand       { from { transform: rotate(-140deg); } to { transform: rotate(-112deg); } }
    @keyframes hatTip {
        0%, 100% { transform: translate(0, 0) rotate(0); }
        40%, 70% { transform: translate(-12px, -30px) rotate(-22deg); }
    }
    @keyframes bow {
        0%, 100% { transform: rotate(0) translateY(0); }
        50%      { transform: rotate(20deg) translateY(8px); }
    }
    @keyframes spark {
        0%   { opacity: 0; transform: translate(0, 0) scale(.3); }
        25%  { opacity: 1; }
        100% { opacity: 0; transform: translate(var(--x), var(--y)) scale(1.2); }
    }
    @keyframes puff { 0% { opacity: .9; transform: scale(.4); } 100% { opacity: 0; transform: scale(4.5); } }

    @media (prefers-reduced-motion: reduce) {
        .show-open { display: none !important; }
    }
</style>

<div class="show-overlay show-{{ $variant }}" id="{{ $id }}" role="presentation">
    <button type="button" class="show-skip" id="{{ $id }}Skip" aria-label="Lewati animasi">Lewati</button>

    <p class="show-line">{{ $line }}</p>

    <div class="show-stage">
        <div class="show-floor"></div>
        <div class="show-man">
            <svg class="magician" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <ellipse cx="100" cy="286" rx="52" ry="7" fill="#000" opacity=".35"/>

                <!-- kaki -->
                <rect x="82" y="190" width="14" height="80" rx="5" fill="#1b1330"/>
                <rect x="76" y="266" width="26" height="12" rx="6" fill="#0c0818"/>
                <rect x="104" y="190" width="14" height="80" rx="5" fill="#1b1330"/>
                <rect x="98" y="266" width="26" height="12" rx="6" fill="#0c0818"/>

                <g class="m-torso">
                    <!-- jubah -->
                    <path d="M72 96 C40 150 36 200 46 232 L100 214 L100 96 Z" fill="#6c3ccf"/>

                    <!-- lengan kiri -->
                    <rect x="62" y="98" width="14" height="66" rx="7" fill="#1b1330"/>
                    <circle cx="69" cy="166" r="8" fill="#f1c9a5"/>

                    <!-- badan -->
                    <path d="M74 94 H126 L132 196 H68 Z" fill="#1b1330"/>
                    <path d="M92 94 L100 130 L108 94 Z" fill="#fff"/>
                    <path d="M94 104 L100 98 L106 104 L100 110 Z" fill="#f2c14e"/>

                    <!-- kepala -->
                    <circle cx="100" cy="72" r="22" fill="#f1c9a5"/>
                    <circle cx="108" cy="68" r="2.6" fill="#1b1330"/>
                    <circle cx="94" cy="68" r="2.6" fill="#1b1330"/>
                    <path d="M92 80 Q100 76 108 80 Q100 86 92 80 Z" fill="#2c1a14"/>

                    <!-- topi -->
                    <g class="m-hat">
                        <rect x="78" y="14" width="44" height="40" rx="3" fill="#120d22"/>
                        <rect x="78" y="40" width="44" height="8" fill="#f2c14e"/>
                        <ellipse cx="100" cy="54" rx="32" ry="6.5" fill="#120d22"/>
                    </g>

                    <!-- lengan kanan + tongkat -->
                    <g class="m-arm-r">
                        <rect x="124" y="98" width="14" height="66" rx="7" fill="#1b1330"/>
                        <circle cx="131" cy="166" r="8" fill="#f1c9a5"/>
                        <rect x="128" y="166" width="5" height="46" rx="2" fill="#fff"/>
                        <rect x="128" y="196" width="5" height="16" rx="2" fill="#1b1330"/>
                    </g>
                </g>

                <!-- kilau & asap di ujung tongkat -->
                <g transform="translate(200 21)">
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