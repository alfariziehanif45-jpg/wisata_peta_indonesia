{{-- 
    Partial Pertunjukan Wisata Nusantara - Turis dengan Koper + Teman Lokal (V8).
    $variant: 'idle' | 'open' | 'close' | 'error'

    Parameter opsional:
      $autoplay  (bool)   -> langsung putar animasi (open/close/error)
      $sub       (string) -> teks kecil di bawah $line (error)
      $greet     (string) -> teks sapaan pada balon ucapan (open/close)

    V8 (baru):
      - Varian idle: seorang teman lokal (berpeci & batik) berjalan masuk dari
        kiri, berhenti di dekat turis, lalu keduanya saling menyapa dan
        mengobrol dengan balon ucapan bergantian (3 skenario acak). Setelah
        selesai, teman itu berjalan pergi lagi. Pertemuan pertama muncul
        sekitar 9 detik setelah halaman dibuka, lalu berulang tiap 22-40 detik.
      - Siklus jalan (kaki & lengan berayun) dan gerakan lambaian tangan kanan.
    V7: balon ucapan + animasi idle acak (melambai, angkat topi, membungkuk, dll).
    V6: id gradient SVG unik per instance.
--}}
@php
    $autoplay = $autoplay ?? false;
    $sub = $sub ?? null;
    $greet = $greet ?? ($variant === 'close' ? 'Sampai jumpa lagi, Kak! 👋' : 'Halo! Selamat datang di Nusantara! 👋');
    $uid = 't' . strtolower(\Illuminate\Support\Str::random(6));
@endphp

@once
<style>
    /* ===== Panggung Nusantara ===== */
    .nusa-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: flex; flex-direction: column; align-items: center; justify-content: flex-end;
        background: radial-gradient(circle at 50% 30%, #1d3b2f 0%, #08100e 80%);
        overflow: hidden; perspective: 1000px;
    }
    .nusa-overlay.is-skipped { display: none !important; }

    /* Lantai Panggung */
    .nusa-floor {
        position: absolute; bottom: 0; left: 0; right: 0; height: 15vh;
        background: repeating-linear-gradient(45deg, #1b2f26 0 10px, #15251e 10px 20px);
        border-top: 6px solid #d4af37;
        box-shadow: inset 0 20px 50px rgba(0,0,0,0.9);
        z-index: 1; transform: rotateX(15deg); transform-origin: bottom;
    }

    /* Ornamen Lampion */
    .lantern {
        position: absolute; z-index: 2; top: -50px;
        width: 30px; height: 40px;
        background: radial-gradient(circle, #ff4d4d 0%, #b30000 100%);
        border-radius: 50% 50% 10% 10%;
        box-shadow: 0 0 20px rgba(255, 77, 77, 0.6);
        animation: floatLantern 4s ease-in-out infinite alternate;
    }
    .lantern::before { content: ''; position: absolute; top: -20px; left: 14px; width: 2px; height: 20px; background: #d4af37; }
    .lantern-1 { left: 10%; animation-delay: 0s; }
    .lantern-2 { right: 10%; animation-delay: 1s; }
    .lantern-3 { left: 25%; animation-delay: 2s; width: 20px; height: 30px; }
    .lantern-4 { right: 25%; animation-delay: 1.5s; width: 20px; height: 30px; }

    /* Tirai Ukir Kayu */
    .nusa-curtain {
        position: absolute; z-index: 3; top: 0; bottom: 0; width: 52%;
        background: repeating-linear-gradient(90deg, #3e2723 0 20px, #4e342e 20px 40px, #2d1b16 40px 60px),
                    repeating-linear-gradient(0deg, transparent 0 50px, rgba(212, 175, 55, 0.2) 50px 52px);
        border-bottom: 6px solid #d4af37;
        transition: transform 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nusa-curtain-l { left: 0; box-shadow: inset -40px 0 60px rgba(0,0,0,0.8); transform-origin: left center; }
    .nusa-curtain-r { right: 0; box-shadow: inset 40px 0 60px rgba(0,0,0,0.8); transform-origin: right center; }

    /* Teks */
    .nusa-line {
        position: absolute; z-index: 5; left: 0; right: 0; top: 15%;
        margin: 0; padding: 20px; text-align: center;
        font: 700 clamp(1.5rem, 4vw, 2.2rem)/1.25 'Playfair Display', serif;
        letter-spacing: 2px; color: #f4d03f;
        text-shadow: 0 0 15px #d4af37, 0 0 30px #b8860b, 0 4px 10px #000;
        opacity: 0; transform: translateY(-20px); transition: all 0.8s ease;
    }
    .nusa-open.is-playing .nusa-line { animation: textGlowIn 1s forwards 1s; }
    .nusa-close.is-playing .nusa-line { animation: textGlowIn 1s forwards 0.5s; }

    /* Tombol Skip */
    .nusa-skip {
        position: absolute; z-index: 10; bottom: 40px; right: 40px;
        padding: 14px 34px; background: linear-gradient(135deg, #4e342e 0%, #2d1b16 100%);
        color: #d4af37; border: 2px solid #d4af37; border-radius: 4px;
        font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: bold;
        letter-spacing: 2px; text-transform: uppercase; cursor: pointer;
        box-shadow: 0 6px 20px rgba(0,0,0,0.8); transition: all 0.3s ease;
    }
    .nusa-skip:hover { background: #d4af37; color: #2d1b16; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(212, 175, 55, 0.5); }

    /* ===== Panggung & Karakter ===== */
    .nusa-stage {
        position: relative; z-index: 2;
        width: min(420px, 92vw); height: 60vh;
        display: flex; align-items: flex-end; justify-content: center; margin-bottom: 5vh;
    }
    .nusa-man {
        position: relative;
        width: min(260px, 60vw); margin-bottom: 14px;
        filter: drop-shadow(0px 10px 15px rgba(0,0,0,0.6));
    }

    .tourist { width: 100%; height: auto; overflow: visible; transform-origin: 50% 100%; }
    .tourist * { transform-box: fill-box; }

    /* Target Animasi Spesifik */
    .t-body { transform-box: view-box; transform-origin: 100px 180px; }
    .t-head { transform-box: view-box; transform-origin: 100px 85px; }
    .t-hat { transform-box: view-box; transform-origin: 100px 50px; }
    .t-arm-l { transform-box: view-box; transform-origin: 70px 105px; }
    .t-arm-r { transform-box: view-box; transform-origin: 130px 105px; }
    .t-foot-l { transform-box: view-box; transform-origin: 85px 250px; }
    .t-foot-r { transform-box: view-box; transform-origin: 115px 250px; }

    .t-sad-face, .t-confuse-mark { opacity: 0; }

    /* ===== Balon Ucapan ===== */
    .t-speech {
        position: absolute; z-index: 6; left: 50%; top: 8%;
        width: max-content; max-width: min(220px, 80vw);
        padding: 10px 16px; text-align: center;
        background: #fdfbf7; color: #2d1b16;
        border: 3px solid #d4af37; border-radius: 14px;
        box-shadow: 0 8px 22px rgba(0,0,0,0.45);
        font: 600 .88rem/1.35 'Poppins', 'Segoe UI', sans-serif;
        transform: translate(-50%, -100%) scale(.6);
        transform-origin: 50% 100%;
        opacity: 0; pointer-events: none;
        transition: opacity .25s ease, transform .35s cubic-bezier(.2, 1.4, .4, 1);
    }
    .t-speech::after {
        content: ''; position: absolute; left: 50%; bottom: -10px;
        width: 14px; height: 14px; background: #fdfbf7;
        border-right: 3px solid #d4af37; border-bottom: 3px solid #d4af37;
        transform: translateX(-50%) rotate(45deg);
    }
    .t-speech.is-visible { opacity: 1; transform: translate(-50%, -100%) scale(1); }
    /* Balon milik teman lokal dibedakan warnanya */
    .t-speech--friend { background: #fff4e0; border-color: #9d0208; }
    .t-speech--friend::after { background: #fff4e0; border-right-color: #9d0208; border-bottom-color: #9d0208; }

    /* ===== ANIMASI IDLE DASAR ===== */
    .nusa-idle-wrapper .nusa-stage { height: 100%; margin-bottom: 20px; }
    .nusa-idle-wrapper .nusa-man {
        margin-top: 80px; /* ruang untuk balon ucapan */
        z-index: 2;
        transition: transform .8s ease;
    }
    .nusa-idle-wrapper .tourist { cursor: pointer; }
    .nusa-idle-wrapper .nusa-floor {
        height: 40px; transform: none; border-radius: 50%; width: 80%; left: 10%;
        border: 2px solid #d4af37; border-top: none;
        background: radial-gradient(ellipse at center, rgba(0,0,0,0.8) 0%, transparent 70%); box-shadow: none;
    }
    .nusa-idle-wrapper .t-body { animation: breathe 3s infinite ease-in-out; }
    .nusa-idle-wrapper .t-head { animation: idleLook 6s infinite ease-in-out; }

    /* Turis bergeser sedikit ke kanan saat ada teman datang */
    .nusa-idle-wrapper.has-visitor .nusa-man { transform: translateX(min(32px, 6vw)); }

    /* ===== TEMAN LOKAL YANG BERJALAN ===== */
    .nusa-walker {
        position: absolute; z-index: 3; left: 10px; bottom: 14px;
        width: min(200px, 44vw);
        filter: drop-shadow(0px 10px 12px rgba(0,0,0,0.55));
        /* posisi awal: di luar panggung sebelah kiri, tersembunyi */
        transform: translateX(-240px);
        opacity: 0; visibility: hidden;
    }
    .nusa-walker.is-present {
        visibility: visible; opacity: 1; transform: none;
        transition: transform 3.4s linear, opacity .6s ease;
    }
    .nusa-walker.is-exiting {
        visibility: visible; opacity: 0; transform: translateX(-240px);
        transition: transform 3.4s linear, opacity .5s ease 2.9s, visibility 0s linear 3.4s;
    }

    /* Siklus berjalan: kaki & lengan berayun, badan naik-turun */
    .tourist.is-walking .t-foot-l { transform-origin: 90px 185px; animation: walkLegL .7s ease-in-out infinite; }
    .tourist.is-walking .t-foot-r { transform-origin: 110px 185px; animation: walkLegR .7s ease-in-out infinite; }
    .tourist.is-walking .t-arm-l { animation: walkArmL .7s ease-in-out infinite; }
    .tourist.is-walking .t-arm-r { animation: walkArmR .7s ease-in-out infinite; }
    .tourist.is-walking .t-body { animation: walkBob .35s ease-in-out infinite; }
    .tourist.is-walking .t-head { animation: walkHead .35s ease-in-out infinite; }

    /* ===== ANIMASI RANDOM (lama) ===== */
    .anim-watch .t-arm-l { animation: checkWatch 2s ease-in-out forwards; }
    .anim-watch .t-head { animation: lookDown 2s ease-in-out forwards; }
    .anim-look-up .t-head { animation: lookUp 2s ease-in-out forwards; }
    .anim-tap .t-foot-r { animation: tapFoot 1.5s ease-in-out forwards; }
    .anim-stretch .t-body { animation: stretchBody 2.5s ease-in-out forwards; }
    .anim-stretch .t-arm-l { animation: stretchArmL 2.5s ease-in-out forwards; }
    .anim-adjust-glasses .t-arm-l { animation: adjustGlasses 1.5s ease-in-out forwards; }
    .anim-nod .t-head { animation: nod 1.5s ease-in-out forwards; }

    /* ===== ANIMASI RANDOM (menyapa & berekspresi) ===== */
    /* Melambai dengan tangan kiri (turis) */
    .anim-wave .t-arm-l { animation: waveHello 2.6s ease-in-out forwards; }
    .anim-wave .t-head { animation: headHello 2.6s ease-in-out forwards; }
    /* Melambai dengan tangan kanan (teman lokal) */
    .anim-wave-r .t-arm-r { animation: waveHelloR 2.6s ease-in-out forwards; }
    .anim-wave-r .t-head { animation: headHelloR 2.6s ease-in-out forwards; }
    /* Angkat topi / peci */
    .anim-hat-tip .t-hat { animation: hatTip 1.8s ease-in-out forwards; }
    .anim-hat-tip .t-head { animation: nod 1.8s ease-in-out forwards; }
    /* Membungkuk memberi salam */
    .anim-bow .t-body { animation: bowBody 2.2s ease-in-out forwards; }
    .anim-bow .t-head { animation: bowHead 2.2s ease-in-out forwards; }
    /* Melompat senang */
    .tourist.anim-jump { animation: jumpAll 1.4s ease-in-out forwards; }
    .anim-jump .t-arm-l { animation: cheerArm 1.4s ease-in-out forwards; }
    /* Menari kecil */
    .tourist.anim-dance { animation: danceAll 1.3s ease-in-out 2 forwards; }
    .anim-dance .t-body { animation: bounceBody .65s ease-in-out 4; }
    .anim-dance .t-arm-l { animation: danceArm 1.3s ease-in-out 2; }
    /* Menunjuk (kiri = turis, kanan = teman lokal) */
    .anim-point .t-arm-l { animation: pointArm 2.4s ease-in-out forwards; }
    .anim-point .t-head { animation: headPoint 2.4s ease-in-out forwards; }
    .anim-point-r .t-arm-r { animation: pointArmR 2.4s ease-in-out forwards; }
    .anim-point-r .t-head { animation: headPointR 2.4s ease-in-out forwards; }
    /* Mengangkat bahu */
    .anim-shrug .t-arm-l { animation: shrugArmL 1.8s ease-in-out forwards; }
    .anim-shrug .t-arm-r { animation: shrugArmR 1.8s ease-in-out forwards; }
    .anim-shrug .t-head { animation: shrugHead 1.8s ease-in-out forwards; }
    /* Celingak-celinguk */
    .anim-look-around .t-head { animation: lookAround 2.8s ease-in-out forwards; }

    /* ===== KEYFRAMES ANIMASI RANDOM ===== */
    @keyframes checkWatch {
        0%, 100% { transform: rotate(0); }
        30% { transform: rotate(-60deg) translate(5px, -15px); }
        70% { transform: rotate(-60deg) translate(5px, -15px); }
    }
    @keyframes lookDown { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(10deg); } }
    @keyframes lookUp { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(-15deg) translateY(-5px); } }
    @keyframes tapFoot { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(-15deg) translateY(-5px); } }
    @keyframes stretchBody { 0%, 100% { transform: scaleY(1); } 50% { transform: scaleY(1.03) translateY(-3px); } }
    @keyframes stretchArmL { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(-130deg); } }
    @keyframes adjustGlasses {
        0%, 100% { transform: rotate(0); }
        50% { transform: rotate(-90deg) translateY(-15px); }
    }

    @keyframes waveHello {
        0%, 100% { transform: rotate(0); }
        12% { transform: rotate(150deg); }
        24% { transform: rotate(168deg); }
        36% { transform: rotate(140deg); }
        48% { transform: rotate(168deg); }
        60% { transform: rotate(140deg); }
        72% { transform: rotate(168deg); }
        86% { transform: rotate(150deg); }
    }
    @keyframes headHello {
        0%, 100% { transform: rotate(0); }
        20%, 80% { transform: rotate(-5deg); }
    }
    @keyframes waveHelloR {
        0%, 100% { transform: rotate(0); }
        12% { transform: rotate(-150deg); }
        24% { transform: rotate(-168deg); }
        36% { transform: rotate(-140deg); }
        48% { transform: rotate(-168deg); }
        60% { transform: rotate(-140deg); }
        72% { transform: rotate(-168deg); }
        86% { transform: rotate(-150deg); }
    }
    @keyframes headHelloR {
        0%, 100% { transform: rotate(0); }
        20%, 80% { transform: rotate(5deg); }
    }
    @keyframes hatTip {
        0%, 100% { transform: translate(0, 0) rotate(0); }
        22%, 72% { transform: translate(-14px, -16px) rotate(-22deg); }
    }
    @keyframes bowBody {
        0%, 100% { transform: scaleY(1) translateY(0); }
        35%, 68% { transform: scaleY(.93) translateY(8px); }
    }
    @keyframes bowHead {
        0%, 100% { transform: rotate(0) translateY(0); }
        35%, 68% { transform: rotate(10deg) translateY(6px); }
    }
    @keyframes jumpAll {
        0% { transform: translateY(0) scale(1, 1); }
        12% { transform: translateY(0) scale(1.05, .94); }
        35% { transform: translateY(-40px) scale(.97, 1.04); }
        55% { transform: translateY(0) scale(1.05, .93); }
        70% { transform: translateY(-14px) scale(1, 1); }
        85% { transform: translateY(0) scale(1.02, .97); }
        100% { transform: translateY(0) scale(1, 1); }
    }
    @keyframes cheerArm {
        0%, 100% { transform: rotate(0); }
        30%, 60% { transform: rotate(155deg); }
    }
    @keyframes danceAll {
        0%, 100% { transform: rotate(0) translateX(0); }
        25% { transform: rotate(-3deg) translateX(-5px); }
        75% { transform: rotate(3deg) translateX(5px); }
    }
    @keyframes bounceBody {
        0%, 100% { transform: translateY(0) scaleY(1); }
        50% { transform: translateY(-5px) scaleY(1.02); }
    }
    @keyframes danceArm {
        0%, 100% { transform: rotate(60deg); }
        50% { transform: rotate(135deg); }
    }
    @keyframes pointArm {
        0%, 100% { transform: rotate(0); }
        20%, 80% { transform: rotate(92deg); }
    }
    @keyframes headPoint {
        0%, 100% { transform: rotate(0) translateX(0); }
        20%, 80% { transform: rotate(-6deg) translateX(-2px); }
    }
    @keyframes pointArmR {
        0%, 100% { transform: rotate(0); }
        20%, 80% { transform: rotate(-92deg); }
    }
    @keyframes headPointR {
        0%, 100% { transform: rotate(0) translateX(0); }
        20%, 80% { transform: rotate(6deg) translateX(2px); }
    }
    @keyframes shrugArmL {
        0%, 100% { transform: rotate(0); }
        25%, 75% { transform: rotate(28deg); }
    }
    @keyframes shrugArmR {
        0%, 100% { transform: rotate(0); }
        25%, 75% { transform: rotate(-22deg); }
    }
    @keyframes shrugHead {
        0%, 100% { transform: rotate(0) translateY(0); }
        25%, 75% { transform: rotate(-7deg) translateY(4px); }
    }
    @keyframes lookAround {
        0%, 100% { transform: rotate(0) translateX(0); }
        20% { transform: rotate(-9deg) translateX(-3px); }
        50% { transform: rotate(9deg) translateX(3px); }
        80% { transform: rotate(-5deg) translateX(-2px); }
    }

    /* Keyframes berjalan */
    @keyframes walkLegL { 0%, 100% { transform: rotate(22deg); } 50% { transform: rotate(-22deg); } }
    @keyframes walkLegR { 0%, 100% { transform: rotate(-22deg); } 50% { transform: rotate(22deg); } }
    @keyframes walkArmL { 0%, 100% { transform: rotate(-16deg); } 50% { transform: rotate(16deg); } }
    @keyframes walkArmR { 0%, 100% { transform: rotate(16deg); } 50% { transform: rotate(-16deg); } }
    @keyframes walkBob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
    @keyframes walkHead { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(1.5deg); } }

    /* ===== BUKA / TUTUP PERTUNJUKAN ===== */
    .nusa-open, .nusa-close { display: none; }
    .nusa-open.is-playing, .nusa-close.is-playing { display: flex; animation: fadeIn .5s ease-out forwards; }
    .nusa-open .nusa-curtain-l { transform: translateX(0); } .nusa-open .nusa-curtain-r { transform: translateX(0); }
    .nusa-open.is-playing .nusa-curtain-l { animation: curtainL 1.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards; }
    .nusa-open.is-playing .nusa-curtain-r { animation: curtainR 1.5s cubic-bezier(0.4, 0, 0.2, 1) 0.5s forwards; }
    .nusa-open.is-playing .t-head { animation: headHello 2.2s ease-in-out 1.6s 1; }
    .nusa-open.is-playing .t-arm-l { animation: waveHello 2.2s ease-in-out 1.6s 1; }
    .nusa-open.is-playing .t-speech { animation: speechIn .45s cubic-bezier(.2, 1.4, .4, 1) 1.7s forwards; }

    .nusa-close .nusa-curtain-l { transform: translateX(-85%); } .nusa-close .nusa-curtain-r { transform: translateX(85%); }
    .nusa-close.is-playing .nusa-curtain-l { animation: closeL 1.5s cubic-bezier(0.4, 0, 0.2, 1) 2.5s forwards; }
    .nusa-close.is-playing .nusa-curtain-r { animation: closeR 1.5s cubic-bezier(0.4, 0, 0.2, 1) 2.5s forwards; }
    .nusa-close.is-playing .t-head { animation: headHello 2.4s ease-in-out .4s 1; }
    .nusa-close.is-playing .t-arm-l { animation: waveHello 2.4s ease-in-out .4s 1; }
    .nusa-close.is-playing .t-speech { animation: speechIn .45s cubic-bezier(.2, 1.4, .4, 1) .6s forwards; }

    /* ===== ERROR ===== */
    .nusa-error { display: none; background: radial-gradient(ellipse at 50% 42%, #9d0208 0%, transparent 62%), linear-gradient(180deg, #08100ef2 0%, #11241ef2 100%); transition: opacity .35s ease; }
    .nusa-error.is-playing { display: flex; animation: fadeIn .35s ease-out; }
    .nusa-error.is-leaving { opacity: 0; }
    .nusa-error .nusa-curtain, .nusa-error .nusa-floor { display: none; }
    .nusa-error .nusa-line {
        top: auto; bottom: calc(50% + min(380px, 70vh) / 2 - 14px); width: fit-content; max-width: min(400px, 90vw);
        margin: 0 auto; padding: 20px 30px; background: #fdfbf7; color: #9d0208; text-shadow: none;
        border: 4px double #d4af37; border-radius: 8px; box-shadow: 0 10px 30px #000a; 
        font-size: clamp(1rem, 4vw, 1.3rem); font-family: 'Playfair Display', serif; opacity: 0; transform: none;
    }
    .nusa-error .nusa-sub { display: block; margin-top: 10px; font: 400 .85rem/1.4 'Poppins', sans-serif; color: #5a5a5a; }
    .nusa-error .nusa-skip { top: auto; right: auto; bottom: 8%; left: 50%; transform: translateX(-50%); padding: 14px 34px; border: 2px solid #d4af37; background: #11241e; color: #d4af37; font-weight: 700; font-size: 1rem; border-radius: 4px; }
    .nusa-error .nusa-skip:hover { background: #d4af37; color: #11241e; transform: translateX(-50%) translateY(-3px) scale(1.05); }
    .nusa-error.is-playing .nusa-line { animation: bubbleIn .5s cubic-bezier(.2, 1.4, .4, 1) .7s forwards; }
    .nusa-error.is-playing .t-sad-face { animation: popIn .01s linear .5s forwards; opacity: 1; }
    .nusa-error.is-playing .t-happy-face { animation: popOut .01s linear .5s forwards; }
    .nusa-error.is-playing .t-head { animation: shakeNo 1.1s ease-in-out .7s 1; }
    .nusa-error.is-playing .t-confuse-mark { animation: qPop .6s cubic-bezier(.2, 1.5, .4, 1) .8s both; opacity: 1; }
    .nusa-error.is-playing .t-body { animation: slump .8s ease-out .6s forwards; }

    /* ===== Keyframes Bawaan ===== */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes curtainL { from { transform: translateX(0); } to { transform: translateX(-85%); } }
    @keyframes curtainR { from { transform: translateX(0); } to { transform: translateX(85%); } }
    @keyframes closeL { from { transform: translateX(-85%); } to { transform: translateX(0); } }
    @keyframes closeR { from { transform: translateX(85%); } to { transform: translateX(0); } }
    @keyframes floatLantern { from { transform: translateY(0); } to { transform: translateY(20px); } }
    @keyframes textGlowIn { to { opacity: 1; transform: translateY(0); } }
    @keyframes bubbleIn { from { opacity: 0; transform: scale(.7) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes speechIn {
        from { opacity: 0; transform: translate(-50%, -100%) scale(.6); }
        to { opacity: 1; transform: translate(-50%, -100%) scale(1); }
    }
    @keyframes popIn { to { opacity: 1; } }
    @keyframes popOut { to { opacity: 0; } }
    @keyframes nod { 0%, 100% { transform: rotate(0); } 25% { transform: rotate(4deg); } 60% { transform: rotate(-3deg); } }
    @keyframes shakeNo { 0%, 100% { transform: rotate(0); } 15% { transform: rotate(-9deg); } 35% { transform: rotate(8deg); } 55% { transform: rotate(-7deg); } 75% { transform: rotate(5deg); } }
    @keyframes slump { to { transform: translateY(4px) rotate(-2deg); } }
    @keyframes qPop { 0% { opacity: 0; transform: scale(0) rotate(-30deg); } 70% { opacity: 1; transform: scale(1.2) rotate(8deg); } 100% { opacity: 1; transform: scale(1) rotate(0); } }
    @keyframes breathe { 0%, 100% { transform: scaleY(1) translateY(0); } 50% { transform: scaleY(1.02) translateY(-2px); } }
    @keyframes idleLook { 0%, 100% { transform: rotate(0); } 25% { transform: rotate(3deg); } 75% { transform: rotate(-3deg); } }
    @keyframes handWave { 0%, 100% { transform: rotate(0); } 50% { transform: rotate(-20deg); } }

    @media (prefers-reduced-motion: reduce) {
        .nusa-open { display: none !important; }
        .nusa-walker { display: none !important; }
        .tourist, .tourist * { animation: none !important; }
        .nusa-error .nusa-line { opacity: 1; }
        .nusa-close .t-speech { opacity: 1; animation: none !important; transform: translate(-50%, -100%) scale(1); }
        .t-speech { transition: opacity .2s ease; }
    }
</style>
@endonce

@php
    // SVG String Karakter Turis.
    // __UID__ diganti dengan id unik per instance supaya gradient tidak bentrok
    // ketika partial ini di-include lebih dari sekali dalam satu halaman.
    $touristSvg = '
    <svg class="tourist" data-uid="__UID__" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="skinGrad-__UID__" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#fce3ca" />
                <stop offset="100%" stop-color="#e8b78f" />
            </linearGradient>
            <linearGradient id="shirtGrad-__UID__" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#2a9d8f" />
                <stop offset="100%" stop-color="#1b6359" />
            </linearGradient>
            <linearGradient id="pantsGrad-__UID__" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stop-color="#e9c46a" />
                <stop offset="100%" stop-color="#c29c41" />
            </linearGradient>
            <linearGradient id="caseGrad-__UID__" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#4a5568" />
                <stop offset="100%" stop-color="#2d3748" />
            </linearGradient>
        </defs>

        <!-- Bayangan -->
        <ellipse cx="100" cy="285" rx="50" ry="8" fill="#000" opacity=".5"/>

        <!-- KOPER -->
        <g class="t-suitcase">
            <rect x="130" y="190" width="40" height="85" rx="6" fill="url(#caseGrad-__UID__)"/>
            <rect x="134" y="194" width="32" height="77" rx="4" fill="none" stroke="#1a202c" stroke-width="2"/>
            <!-- Handle Koper -->
            <path d="M140 190 L140 155 L155 155 L155 190" fill="none" stroke="#cbd5e0" stroke-width="4" stroke-linejoin="round"/>
            <!-- Roda -->
            <circle cx="138" cy="278" r="5" fill="#1a202c"/>
            <circle cx="162" cy="278" r="5" fill="#1a202c"/>
            <!-- Label -->
            <circle cx="150" cy="245" r="7" fill="#ecc94b"/>
        </g>

        <g class="t-body">
            <!-- KAKI KIRI -->
            <g class="t-foot-l">
                <path d="M85 185 Q80 220 82 255 L92 255 Q92 220 95 185 Z" fill="url(#pantsGrad-__UID__)"/>
                <path d="M82 250 L82 265 L92 265 L92 250 Z" fill="url(#skinGrad-__UID__)"/>
                <path d="M75 265 Q85 260 95 265 Q100 275 95 280 L70 280 Z" fill="#f1f1f1"/>
                <path d="M70 275 L100 275" stroke="#cbd5e0" stroke-width="2"/>
            </g>

            <!-- KAKI KANAN -->
            <g class="t-foot-r">
                <path d="M115 185 Q120 220 118 255 L108 255 Q108 220 105 185 Z" fill="url(#pantsGrad-__UID__)"/>
                <path d="M118 250 L118 265 L108 265 L108 250 Z" fill="url(#skinGrad-__UID__)"/>
                <path d="M125 265 Q115 260 105 265 Q100 275 105 280 L130 280 Z" fill="#f1f1f1"/>
                <path d="M130 275 L100 275" stroke="#cbd5e0" stroke-width="2"/>
            </g>

            <!-- BADAN -->
            <path d="M70 100 Q100 90 130 100 L125 190 Q100 200 75 190 Z" fill="url(#shirtGrad-__UID__)"/>
            <!-- Kerah -->
            <path d="M85 100 L100 115 L115 100 Z" fill="#1b6359"/>
            <circle cx="100" cy="120" r="1.5" fill="#fff"/>
            <circle cx="100" cy="132" r="1.5" fill="#fff"/>

            <!-- Tali Tas Selempang -->
            <path d="M75 100 L120 165" stroke="#8c5a35" stroke-width="5" fill="none"/>

            <!-- LENGAN KIRI -->
            <g class="t-arm-l">
                <path d="M70 105 Q60 140 65 175 L75 175 Q70 140 80 110 Z" fill="url(#shirtGrad-__UID__)"/>
                <ellipse cx="70" cy="177" rx="6" ry="8" fill="url(#skinGrad-__UID__)"/> <!-- Tangan -->
            </g>

            <!-- KEPALA -->
            <g class="t-head">
                <rect x="90" y="85" width="20" height="15" fill="#e0b08c"/> <!-- Leher -->
                
                <circle cx="78" cy="65" r="5" fill="#e0b08c"/> <!-- Telinga Kiri -->
                <circle cx="122" cy="65" r="5" fill="#e0b08c"/> <!-- Telinga Kanan -->

                <ellipse cx="100" cy="65" rx="21" ry="23" fill="url(#skinGrad-__UID__)"/> <!-- Wajah -->
                
                <!-- Rambut -->
                <path d="M79 55 Q85 38 100 38 Q115 38 121 55 Q110 48 100 48 Q90 48 79 55 Z" fill="#2d1b16"/>

                <!-- Kacamata Hitam -->
                <path d="M81 62 Q90 56 97 62 L97 68 Q90 72 81 68 Z" fill="#111"/>
                <path d="M119 62 Q110 56 103 62 L103 68 Q110 72 119 68 Z" fill="#111"/>
                <path d="M97 64 L103 64" stroke="#111" stroke-width="2"/>
                <path d="M84 62 L89 62" stroke="#fff" stroke-width="1.5" opacity="0.4"/>
                <path d="M116 62 L111 62" stroke="#fff" stroke-width="1.5" opacity="0.4"/>

                <!-- Hidung -->
                <path d="M100 66 Q103 74 98 76" stroke="#d29a74" stroke-width="1.5" fill="none" stroke-linecap="round"/>

                <!-- Mulut -->
                <path class="t-happy-face" d="M92 82 Q100 86 108 82" stroke="#8a3a2a" stroke-width="2" fill="none" stroke-linecap="round"/>
                <path class="t-sad-face" d="M92 86 Q100 80 108 86" stroke="#8a3a2a" stroke-width="2" fill="none" stroke-linecap="round"/>

                <!-- TOPI BUCKET -->
                <g class="t-hat">
                    <path d="M77 50 Q77 22 100 22 Q123 22 123 50 Z" fill="#e6c28f"/> <!-- Crown -->
                    <path d="M77 45 Q100 52 123 45 L123 50 Q100 57 77 50 Z" fill="#2a9d8f"/> <!-- Pita -->
                    <path d="M65 50 Q100 65 135 50 Q100 42 65 50 Z" fill="#d4af37"/> <!-- Brim -->
                </g>

                <text class="t-confuse-mark" x="135" y="40" font-family="Georgia, serif" font-size="28" font-weight="700" fill="#d4af37">?</text>
            </g>

            <!-- LENGAN KANAN & TANGAN (Menggenggam Koper) -->
            <g class="t-arm-r">
                <!-- Lengan Baju -->
                <path d="M130 105 Q140 130 145 160 L135 160 Q130 130 120 110 Z" fill="url(#shirtGrad-__UID__)"/>
                <!-- Tangan Menggenggam Handle -->
                <ellipse cx="147" cy="160" rx="6" ry="8" fill="url(#skinGrad-__UID__)"/>
            </g>
        </g>
    </svg>
    ';
    $touristSvg = str_replace('__UID__', $uid, $touristSvg);

    // SVG String Teman Lokal (berpeci hitam, kemeja batik, tas anyaman).
    // Struktur kelompok (t-body, t-head, t-arm-l, t-arm-r, t-foot-l, t-foot-r, t-hat)
    // sengaja sama dengan turis agar semua animasi CSS bisa dipakai bersama.
    $friendSvg = '';
    if ($variant === 'idle') {
        $friendSvg = '
        <svg class="tourist friend" data-uid="__UID__" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="fSkin-__UID__" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#e3ae7e" />
                    <stop offset="100%" stop-color="#b97c4f" />
                </linearGradient>
                <linearGradient id="fShirt-__UID__" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#a63a24" />
                    <stop offset="100%" stop-color="#5e1710" />
                </linearGradient>
                <linearGradient id="fPants-__UID__" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#2f3142" />
                    <stop offset="100%" stop-color="#1a1b27" />
                </linearGradient>
            </defs>

            <!-- Bayangan -->
            <ellipse cx="100" cy="285" rx="46" ry="8" fill="#000" opacity=".5"/>

            <g class="t-body">
                <!-- KAKI KIRI -->
                <g class="t-foot-l">
                    <path d="M85 185 Q80 220 82 255 L92 255 Q92 220 95 185 Z" fill="url(#fPants-__UID__)"/>
                    <path d="M82 250 L82 265 L92 265 L92 250 Z" fill="url(#fSkin-__UID__)"/>
                    <path d="M75 265 Q85 260 95 265 Q100 275 95 280 L70 280 Z" fill="#6b3f22"/>
                    <path d="M70 275 L100 275" stroke="#4a2a15" stroke-width="2"/>
                </g>

                <!-- KAKI KANAN -->
                <g class="t-foot-r">
                    <path d="M115 185 Q120 220 118 255 L108 255 Q108 220 105 185 Z" fill="url(#fPants-__UID__)"/>
                    <path d="M118 250 L118 265 L108 265 L108 250 Z" fill="url(#fSkin-__UID__)"/>
                    <path d="M125 265 Q115 260 105 265 Q100 275 105 280 L130 280 Z" fill="#6b3f22"/>
                    <path d="M130 275 L100 275" stroke="#4a2a15" stroke-width="2"/>
                </g>

                <!-- BADAN (kemeja batik) -->
                <path d="M70 100 Q100 90 130 100 L125 190 Q100 200 75 190 Z" fill="url(#fShirt-__UID__)"/>
                <!-- Motif batik -->
                <g fill="#e9c46a" opacity=".85">
                    <path d="M90 125 l3 3 l-3 3 l-3 -3 z"/>
                    <path d="M110 125 l3 3 l-3 3 l-3 -3 z"/>
                    <path d="M100 142 l3 3 l-3 3 l-3 -3 z"/>
                    <path d="M84 156 l3 3 l-3 3 l-3 -3 z"/>
                    <path d="M116 156 l3 3 l-3 3 l-3 -3 z"/>
                    <path d="M100 172 l3 3 l-3 3 l-3 -3 z"/>
                    <circle cx="90" cy="143" r="1.6"/>
                    <circle cx="110" cy="143" r="1.6"/>
                    <circle cx="100" cy="158" r="1.6"/>
                    <circle cx="90" cy="173" r="1.6"/>
                    <circle cx="110" cy="173" r="1.6"/>
                </g>
                <!-- Kerah -->
                <path d="M85 100 L100 115 L115 100 Z" fill="#e9c46a"/>

                <!-- Tali & Tas Anyaman -->
                <path d="M125 100 L82 160" stroke="#b5651d" stroke-width="5" fill="none"/>
                <rect x="78" y="158" width="26" height="23" rx="4" fill="#c9a15c"/>
                <path d="M78 165 L104 165 M78 172 L104 172 M85 158 L85 181 M92 158 L92 181 M99 158 L99 181" stroke="#8c6a30" stroke-width="1.2" fill="none"/>

                <!-- LENGAN KIRI -->
                <g class="t-arm-l">
                    <path d="M70 105 Q60 140 65 175 L75 175 Q70 140 80 110 Z" fill="url(#fShirt-__UID__)"/>
                    <ellipse cx="70" cy="177" rx="6" ry="8" fill="url(#fSkin-__UID__)"/>
                </g>

                <!-- KEPALA -->
                <g class="t-head">
                    <rect x="90" y="85" width="20" height="15" fill="#b97c4f"/> <!-- Leher -->

                    <circle cx="78" cy="65" r="5" fill="#c98f5f"/>
                    <circle cx="122" cy="65" r="5" fill="#c98f5f"/>

                    <ellipse cx="100" cy="65" rx="21" ry="23" fill="url(#fSkin-__UID__)"/> <!-- Wajah -->

                    <!-- Rambut -->
                    <path d="M79 55 Q85 42 100 42 Q115 42 121 55 Q110 49 100 49 Q90 49 79 55 Z" fill="#1b110c"/>

                    <!-- Alis -->
                    <path d="M86 57 Q91 54 96 57" stroke="#1b110c" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <path d="M104 57 Q109 54 114 57" stroke="#1b110c" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <!-- Mata -->
                    <circle cx="91" cy="64" r="2.4" fill="#1b110c"/>
                    <circle cx="109" cy="64" r="2.4" fill="#1b110c"/>
                    <circle cx="91.8" cy="63.2" r=".8" fill="#fff"/>
                    <circle cx="109.8" cy="63.2" r=".8" fill="#fff"/>

                    <!-- Hidung -->
                    <path d="M100 66 Q103 74 98 76" stroke="#9a6238" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                    <!-- Kumis -->
                    <path d="M91 79 Q100 74 109 79 Q100 81 91 79 Z" fill="#1b110c"/>
                    <!-- Mulut -->
                    <path class="t-happy-face" d="M92 84 Q100 89 108 84" stroke="#7a2e1f" stroke-width="2" fill="none" stroke-linecap="round"/>

                    <!-- PECI HITAM -->
                    <g class="t-hat">
                        <path d="M78 50 Q78 28 100 28 Q122 28 122 50 Q100 55 78 50 Z" fill="#171717"/>
                        <path d="M78 46 Q100 52 122 46 L122 50 Q100 56 78 50 Z" fill="#e9c46a"/>
                    </g>
                </g>

                <!-- LENGAN KANAN -->
                <g class="t-arm-r">
                    <path d="M130 105 Q140 130 145 160 L135 160 Q130 130 120 110 Z" fill="url(#fShirt-__UID__)"/>
                    <ellipse cx="147" cy="162" rx="6" ry="8" fill="url(#fSkin-__UID__)"/>
                </g>
            </g>
        </svg>
        ';
        $friendSvg = str_replace('__UID__', $uid . 'f', $friendSvg);
    }
@endphp

@if ($variant === 'idle')
    <div class="nusa-idle-wrapper" id="touristIdleContainer-{{ $uid }}">
        <div class="nusa-stage">
            <div class="nusa-floor"></div>

            <div class="nusa-man">
                <div class="t-speech" role="status" aria-live="polite"></div>
                {!! $touristSvg !!}
            </div>

            <div class="nusa-walker" aria-hidden="false">
                <div class="t-speech t-speech--friend" role="status" aria-live="polite"></div>
                {!! $friendSvg !!}
            </div>
        </div>
    </div>
@else
    <div class="nusa-overlay nusa-{{ $variant }} {{ $autoplay ? 'is-playing' : '' }}" id="{{ $id }}" role="{{ $variant === 'error' ? 'alert' : 'presentation' }}">
        <div class="lantern lantern-1"></div>
        <div class="lantern lantern-2"></div>
        <div class="lantern lantern-3"></div>
        <div class="lantern lantern-4"></div>

        <button type="button" class="nusa-skip" id="{{ $id }}Skip" aria-label="{{ $variant === 'error' ? 'Tutup pesan dan coba lagi' : 'Lewati animasi' }}">
            <span>{{ $variant === 'error' ? 'Kembali' : 'Lewati' }}</span>
        </button>
        
        <div class="nusa-curtain nusa-curtain-l"></div>
        <div class="nusa-curtain nusa-curtain-r"></div>
        <div class="nusa-floor"></div>

        <p class="nusa-line">{{ $line }}@if ($sub)<span class="nusa-sub">{{ $sub }}</span>@endif</p>
        
        <div class="nusa-stage">
            <div class="nusa-man">
                @if ($variant !== 'error')
                    <div class="t-speech" aria-hidden="true">{{ $greet }}</div>
                @endif
                {!! $touristSvg !!}
            </div>
        </div>
    </div>
@endif

@if ($variant === 'idle')
<script>
    (function () {
        function init() {
            var container = document.getElementById('touristIdleContainer-{{ $uid }}');
            if (!container) return;

            var walker = container.querySelector('.nusa-walker');
            var tSvg = container.querySelector('.nusa-man .tourist');
            var tBubble = container.querySelector('.nusa-man .t-speech');
            var fSvg = walker ? walker.querySelector('.tourist') : null;
            var fBubble = walker ? walker.querySelector('.t-speech') : null;
            if (!tSvg || !tBubble) return;

            var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var WALK_MS = 3400; // harus sama dengan durasi transisi .nusa-walker di CSS

            // Durasi (ms) tiap kelas animasi.
            var DUR = {
                'anim-wave': 2700, 'anim-wave-r': 2700, 'anim-hat-tip': 1900, 'anim-bow': 2300,
                'anim-jump': 1500, 'anim-dance': 2700, 'anim-point': 2500, 'anim-point-r': 2500,
                'anim-shrug': 1900, 'anim-look-around': 2900, 'anim-watch': 2100,
                'anim-stretch': 2600, 'anim-nod': 1600, 'anim-look-up': 2100,
                'anim-tap': 1600, 'anim-adjust-glasses': 1600
            };
            var ANIM_CLASSES = Object.keys(DUR);

            function rand(arr) { return arr[Math.floor(Math.random() * arr.length)]; }

            // Pembungkus satu karakter: balon ucapan + animasi.
            function makeActor(svg, bubble) {
                var bubbleTimer = null, animTimer = null;
                return {
                    say: function (text, ms) {
                        clearTimeout(bubbleTimer);
                        bubble.textContent = text;
                        bubble.classList.add('is-visible');
                        bubbleTimer = setTimeout(function () {
                            bubble.classList.remove('is-visible');
                        }, ms);
                    },
                    hide: function () {
                        clearTimeout(bubbleTimer);
                        bubble.classList.remove('is-visible');
                    },
                    stop: function () {
                        clearTimeout(animTimer);
                        ANIM_CLASSES.forEach(function (c) { svg.classList.remove(c); });
                    },
                    act: function (cls, ms, cb) {
                        this.stop();
                        void svg.getBoundingClientRect(); // restart animasi
                        svg.classList.add(cls);
                        animTimer = setTimeout(function () {
                            svg.classList.remove(cls);
                            if (cb) cb();
                        }, ms);
                    }
                };
            }

            var tourist = makeActor(tSvg, tBubble);
            var friend = (fSvg && fBubble) ? makeActor(fSvg, fBubble) : null;

            /* ---------- Animasi acak turis ---------- */
            var actions = [
                { cls: 'anim-wave', say: [
                    'Halo, selamat datang! 👋',
                    'Hai, Kak! Mau jalan-jalan ke Nusantara?',
                    'Selamat datang! Semoga betah ya! 😊'
                ]},
                { cls: 'anim-hat-tip', say: ['Permisi, Kak! 🎩', 'Selamat datang, Tuan dan Puan!'] },
                { cls: 'anim-bow', say: ['Salam kenal dari Nusantara 🙏', 'Terima kasih sudah mampir!'] },
                { cls: 'anim-jump', say: ['Wah, seru banget! 🎉', 'Yey, liburan dimulai!'] },
                { cls: 'anim-dance', say: ['Yuk, goyang sedikit! 💃', 'Tarian dulu biar semangat!'] },
                { cls: 'anim-point', say: ['Lihat itu, indah sekali!', 'Di sana ada destinasi menarik lho!'] },
                { cls: 'anim-shrug', say: ['Hmm, mau ke mana dulu ya?', 'Banyak pilihan, jadi bingung! 🤔'] },
                { cls: 'anim-look-around', say: ['Wah, tempatnya bagus...', 'Eh, tadi lewat mana ya?'] },
                { cls: 'anim-watch', say: ['Sudah jam berapa ya? ⌚'] },
                { cls: 'anim-stretch', say: ['Hoaam, pegal juga... 😴'] },
                { cls: 'anim-nod', say: ['Iya, iya, betul sekali!'] },
                { cls: 'anim-look-up', say: [] },
                { cls: 'anim-tap', say: [] },
                { cls: 'anim-adjust-glasses', say: [] }
            ];
            var wave = actions[0];
            var busy = false, timer = null, last = -1;

            function pick() {
                var i;
                do { i = Math.floor(Math.random() * actions.length); }
                while (i === last && actions.length > 1);
                last = i;
                return actions[i];
            }

            function schedule(delay) {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    if (document.hidden || busy) { schedule(1500); return; }
                    playAction(pick());
                }, delay);
            }

            function playAction(a) {
                var dur = DUR[a.cls];
                if (a.say.length) tourist.say(rand(a.say), dur + 700);
                tourist.act(a.cls, dur, function () {
                    schedule(2500 + Math.floor(Math.random() * 2500));
                });
            }

            // Klik turis -> langsung menyapa (kecuali sedang mengobrol).
            tSvg.addEventListener('click', function () {
                if (busy) return;
                clearTimeout(timer);
                last = 0;
                playAction(wave);
            });

            /* ---------- Pertemuan dengan teman lokal ---------- */
            // who: 'tourist' | 'friend'. hold = lama balon tampil (ms).
            var scripts = [
                [
                    { who: 'friend',  say: 'Halo, Mas! Baru sampai ya? 👋', act: 'anim-wave-r', hold: 2800 },
                    { who: 'tourist', say: 'Halo! Iya, baru turun dari pesawat! ✈️', act: 'anim-wave', hold: 3000 },
                    { who: 'friend',  say: 'Selamat datang di Nusantara! 🙏', act: 'anim-bow', hold: 2800 },
                    { who: 'tourist', say: 'Terima kasih! Ada tempat yang wajib dikunjungi?', act: 'anim-nod', hold: 3200 },
                    { who: 'friend',  say: 'Banyak! Candi, pantai, gunung... semuanya indah!', act: 'anim-point-r', hold: 3200 },
                    { who: 'tourist', say: 'Wah, mantap! Aku jelajahi semuanya! 🎉', act: 'anim-jump', hold: 2800 },
                    { who: 'friend',  say: 'Sampai jumpa, hati-hati di jalan! 👋', act: 'anim-wave-r', hold: 2800 },
                    { who: 'tourist', say: 'Sampai jumpa! 👋', act: 'anim-wave', hold: 2400 }
                ],
                [
                    { who: 'friend',  say: 'Permisi, selamat siang! 🎩', act: 'anim-hat-tip', hold: 2400 },
                    { who: 'tourist', say: 'Selamat siang! Tempat ini indah sekali.', act: 'anim-hat-tip', hold: 3000 },
                    { who: 'friend',  say: 'Sudah coba rendang atau sate belum?', act: 'anim-nod', hold: 3000 },
                    { who: 'tourist', say: 'Belum! Hmm, mau coba yang mana dulu ya?', act: 'anim-shrug', hold: 3000 },
                    { who: 'friend',  say: 'Coba semuanya! Dijamin ketagihan 😄', act: 'anim-jump', hold: 2800 },
                    { who: 'tourist', say: 'Siap! Terima kasih banyak, Pak!', act: 'anim-bow', hold: 2800 },
                    { who: 'friend',  say: 'Sama-sama! Selamat berlibur!', act: 'anim-wave-r', hold: 2600 },
                    { who: 'tourist', say: 'Dadah! 👋', act: 'anim-wave', hold: 2300 }
                ],
                [
                    { who: 'friend',  say: 'Hai! Kamu turis ya? Selamat datang! 😊', act: 'anim-wave-r', hold: 3000 },
                    { who: 'tourist', say: 'Hai! Iya, ini pertama kalinya aku ke sini.', act: 'anim-wave', hold: 3000 },
                    { who: 'friend',  say: 'Nanti sore ada pertunjukan tari lho!', act: 'anim-point-r', hold: 3000 },
                    { who: 'tourist', say: 'Wah, aku mau lihat! Yuk, goyang dulu! 💃', act: 'anim-dance', hold: 3000 },
                    { who: 'friend',  say: 'Haha, semangat sekali! Sampai nanti ya!', act: 'anim-jump', hold: 2800 },
                    { who: 'tourist', say: 'Sampai nanti! 👋', act: 'anim-wave', hold: 2400 }
                ]
            ];
            var lastScript = -1;
            var meetTimer = null;

            function pickScript() {
                var i;
                do { i = Math.floor(Math.random() * scripts.length); }
                while (i === lastScript && scripts.length > 1);
                lastScript = i;
                return scripts[i];
            }

            function runSteps(steps, done) {
                var i = 0;
                (function next() {
                    if (i >= steps.length) { setTimeout(done, 400); return; }
                    var s = steps[i++];
                    var me = s.who === 'friend' ? friend : tourist;
                    var other = s.who === 'friend' ? tourist : friend;
                    var hold = s.hold || 2800;
                    other.hide();
                    me.say(s.say, hold);
                    if (s.act) me.act(s.act, DUR[s.act] || 1800);
                    setTimeout(next, hold + 350);
                })();
            }

            function planMeet(ms) {
                if (!friend || reduce) return;
                clearTimeout(meetTimer);
                meetTimer = setTimeout(meet, ms || (22000 + Math.floor(Math.random() * 18000)));
            }

            function leave() {
                fSvg.classList.add('is-walking');
                walker.classList.remove('is-present');
                walker.classList.add('is-exiting');
                // Turis kembali ke posisi tengah.
                setTimeout(function () { container.classList.remove('has-visitor'); }, 1200);
                setTimeout(function () {
                    walker.classList.remove('is-exiting');
                    fSvg.classList.remove('is-walking');
                    friend.stop();
                    friend.hide();
                    busy = false;
                    schedule(2500);
                    planMeet();
                }, WALK_MS + 200);
            }

            function meet() {
                if (!friend || busy) return;
                if (document.hidden) { planMeet(3000); return; }
                busy = true;
                clearTimeout(timer);
                tourist.stop();
                tourist.hide();

                container.classList.add('has-visitor');
                fSvg.classList.add('is-walking');
                walker.classList.remove('is-exiting');
                void walker.offsetWidth; // pastikan transisi mulai dari posisi awal
                walker.classList.add('is-present');

                // Turis menoleh karena mendengar langkah kaki.
                setTimeout(function () { tourist.act('anim-look-around', 2400); }, 1200);

                // Teman tiba -> mulai obrolan -> lalu pergi.
                setTimeout(function () {
                    fSvg.classList.remove('is-walking');
                    runSteps(pickScript(), leave);
                }, WALK_MS + 150);
            }

            // Pemicu manual dari luar, mis. document.getElementById('touristIdleContainer-xxx').nusaMeet()
            container.nusaMeet = meet;

            /* ---------- Mulai ---------- */
            // Sapaan pertama setelah halaman siap: selalu melambai.
            timer = setTimeout(function () { last = 0; playAction(wave); }, 1200);
            // Teman lokal pertama lewat sekitar 9 detik kemudian.
            planMeet(9000);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
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
        overlay.nusaPlay = function () {
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
        @if ($autoplay) overlay.nusaPlay(); @endif
    })();
</script>
@endif