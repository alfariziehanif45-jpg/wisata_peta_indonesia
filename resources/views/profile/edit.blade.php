<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil - Jelajah Wisata Indonesia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --red: #e11d48;
            --red-dark: #be123c;
            --red-soft: #fff1f2;
            --orange: #fb923c;
            --ink: #0f172a;
            --ink-2: #334155;
            --muted: #64748b;
            --line: rgba(15, 23, 42, .09);
            --bg: #f4f5fa;
            --card: rgba(255, 255, 255, .88);
            --grad: linear-gradient(135deg, #e11d48 0%, #f43f5e 45%, #fb923c 100%);
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, .04), 0 4px 14px rgba(15, 23, 42, .05);
            --shadow: 0 2px 4px rgba(15, 23, 42, .04), 0 18px 40px rgba(15, 23, 42, .08);
            --ring: 0 0 0 4px rgba(225, 29, 72, .14);
        }

        * { box-sizing: border-box; }

        /* Penting: atribut hidden harus selalu menyembunyikan elemen */
        [hidden] { display: none !important; }

        html { scroll-behavior: smooth; background: var(--bg); }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            font-family: "Plus Jakarta Sans", "Inter", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
            background:
                radial-gradient(900px 500px at 5% -5%, rgba(244, 63, 94, .15), transparent 60%),
                radial-gradient(800px 500px at 100% 0%, rgba(251, 146, 60, .15), transparent 55%),
                radial-gradient(700px 500px at 50% 110%, rgba(99, 102, 241, .09), transparent 60%),
                var(--bg);
            background-attachment: fixed;
        }

        .container { max-width: 820px; margin: auto; padding: 26px 18px 40px; }

        a { color: inherit; text-decoration: none; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim { animation: fadeUp .55s cubic-bezier(.2, .7, .2, 1) both; }
        .d1 { animation-delay: .06s; }
        .d2 { animation-delay: .12s; }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .anim { animation: none; }
            * { transition: none !important; }
        }

        .back {
            display: inline-flex; align-items: center; gap: 6px;
            margin-bottom: 18px; padding: 9px 16px;
            background: rgba(255, 255, 255, .85);
            -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .8); border-radius: 999px;
            font-size: 14px; font-weight: 700; color: var(--ink-2);
            box-shadow: var(--shadow-sm);
            transition: transform .15s, box-shadow .15s;
        }
        .back:hover { transform: translateX(-3px); box-shadow: var(--shadow); }

        /* ===== HERO ===== */
        .hero {
            position: relative; overflow: hidden;
            display: flex; align-items: center; gap: 22px;
            padding: 30px 32px; margin-bottom: 22px; border-radius: 28px; color: #fff;
            background:
                radial-gradient(circle at 12% 20%, rgba(255,255,255,.28), transparent 42%),
                linear-gradient(120deg, #9f1239, #e11d48 50%, #fb923c);
            box-shadow: 0 18px 40px rgba(225, 29, 72, .28);
        }
        .hero::before {
            content: ""; position: absolute; width: 300px; height: 300px;
            right: -70px; top: -120px; border-radius: 50%; background: rgba(255, 255, 255, .14);
        }
        .hero::after {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(rgba(255,255,255,.2) 1.5px, transparent 1.5px);
            background-size: 22px 22px; opacity: .5;
        }
        .hero > * { position: relative; z-index: 1; }

        .hero .avatar {
            flex: none; width: 88px; height: 88px; border-radius: 50%; object-fit: cover;
            border: 4px solid rgba(255,255,255,.95); background: #fff7ed;
            display: grid; place-items: center; font-size: 40px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .22);
        }
        .hero h1 { margin: 0 0 4px; font-size: 28px; font-weight: 800; letter-spacing: -.03em; }
        .hero p { margin: 0; opacity: .92; font-size: 15px; font-weight: 500; }

        /* ===== ALERT ===== */
        .success, .error {
            padding: 14px 18px; border-radius: 16px; margin-bottom: 18px;
            font-size: 14px; font-weight: 500;
        }
        .success { background: rgba(240, 253, 244, .95); border: 1px solid #bbf7d0; color: #166534; }
        .error   { background: rgba(254, 242, 242, .95); border: 1px solid #fecaca; color: #991b1b; }
        .error ul { margin: 0; padding-left: 20px; }

        /* ===== FORM & SECTION CARD ===== */
        .section {
            background: var(--card);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, .75);
            border-radius: 26px;
            box-shadow: var(--shadow);
            padding: 26px 28px 30px;
            margin-bottom: 20px;
        }

        .title {
            display: flex; align-items: center; gap: 12px;
            font-size: 19px; font-weight: 800; margin: 0 0 4px; letter-spacing: -.02em;
        }
        .title .ico {
            width: 40px; height: 40px; display: grid; place-items: center; flex: none;
            border-radius: 13px; background: var(--grad); color: #fff; font-size: 18px;
            box-shadow: 0 8px 18px rgba(225, 29, 72, .28);
        }
        .section-desc { margin: 0 0 6px 52px; color: var(--muted); font-size: 13.5px; }

        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }
        .full { grid-column: 1 / -1; }

        label { display: block; font-size: 13.5px; font-weight: 700; margin: 18px 0 8px; color: var(--ink-2); }

        input[type=text], input[type=email], input[type=password], input:not([type]), textarea {
            width: 100%; padding: 13px 15px; border: 1.5px solid #e2e8f0; border-radius: 14px;
            font-size: 15px; font-family: inherit; color: var(--ink); background: #f8fafc;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        input:focus, textarea:focus {
            outline: none; border-color: var(--red); background: #fff; box-shadow: var(--ring);
        }
        input.is-invalid, textarea.is-invalid { border-color: #f87171; }
        textarea { min-height: 130px; resize: vertical; line-height: 1.65; }

        .field-error { display: block; margin-top: 6px; color: var(--red-dark); font-size: 12.5px; font-weight: 600; }
        .hint { font-size: 12.5px; color: var(--muted); margin: 10px 0 0; }
        .counter { text-align: right; font-size: 12px; color: var(--muted); margin-top: 6px; font-weight: 600; }

        .input-prefix { position: relative; }
        .input-prefix span {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
            color: var(--red); font-weight: 800;
        }
        .input-prefix input { padding-left: 34px; }

        .pw { position: relative; }
        .pw input { padding-right: 96px; }
        .pw button {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; color: var(--red);
            font-size: 13px; font-weight: 800; cursor: pointer; padding: 8px; font-family: inherit;
        }

        /* ===== PREVIEW KOTAK (sampul & background) ===== */
        .cover-preview {
            position: relative; margin-top: 18px; height: 180px; border-radius: 20px; overflow: hidden;
            background:
                radial-gradient(circle at 12% 20%, rgba(255,255,255,.25), transparent 40%),
                linear-gradient(120deg, var(--red-dark), var(--red) 55%, var(--orange));
            box-shadow: inset 0 0 0 1px rgba(0,0,0,.06), var(--shadow-sm);
        }
        /* Gambar/video ditampilkan apa adanya (tanpa filter, tanpa blur).
           Posisi & zoom diatur lewat object-position + transform (inline dari JS). */
        .cover-preview img, .cover-preview video {
            position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: block;
            filter: none; image-rendering: auto;
            will-change: transform;
            backface-visibility: hidden;
            -webkit-user-drag: none;
            user-select: none;
            pointer-events: none;
        }
        .cover-badge {
            position: absolute; left: 12px; bottom: 12px; z-index: 2;
            padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;
            color: #fff; background: rgba(15, 23, 42, .6);
            -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
            pointer-events: none;
        }

        /* Partikel salju di pratinjau background */
        .snow-canvas {
            position: absolute; inset: 0; width: 100%; height: 100%;
            z-index: 1; pointer-events: none; display: none;
        }

        /* Hiasan salju di pinggir pratinjau (atas & bawah), tengah tetap polos */
        .cover-preview .snow-frame,
        .cover-preview .snow-side { display: none; position: absolute; pointer-events: none; z-index: 1; }
        .cover-preview.is-salju .snow-frame,
        .cover-preview.is-salju .snow-side { display: block; }

        .cover-preview .snow-frame {
            left: 0; right: 0; height: 50%;
            background-repeat: repeat-x; background-size: 240px auto;
        }
        .cover-preview .snow-frame.top {
            top: 0; background-position: top center;
            -webkit-mask-image: linear-gradient(to bottom, #000 50%, transparent);
            mask-image: linear-gradient(to bottom, #000 50%, transparent);
        }
        .cover-preview .snow-frame.bottom {
            bottom: 0; background-position: bottom center;
            -webkit-mask-image: linear-gradient(to top, #000 50%, transparent);
            mask-image: linear-gradient(to top, #000 50%, transparent);
        }
        .cover-preview .snow-side { top: 0; bottom: 0; width: 48px; }
        .cover-preview .snow-side.left  { left: 0;  background: linear-gradient(to right, rgba(255,255,255,.55), transparent); }
        .cover-preview .snow-side.right { right: 0; background: linear-gradient(to left,  rgba(255,255,255,.55), transparent); }

        /* Mode atur posisi sampul (foto/video bisa digeser) */
        .cover-preview.adjustable {
            cursor: grab;
            touch-action: none;
            user-select: none;
            -webkit-user-select: none;
            outline: 3px solid rgba(225, 29, 72, .0);
            transition: outline-color .15s;
        }
        .cover-preview.adjustable:hover { outline-color: rgba(225, 29, 72, .35); }
        .cover-preview.adjustable.dragging { cursor: grabbing; outline-color: rgba(225, 29, 72, .6); }

        .cover-hint {
            display: none;
            position: absolute; right: 12px; top: 12px; z-index: 2;
            padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;
            color: #fff; background: rgba(15, 23, 42, .6);
            -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
            pointer-events: none;
        }
        .cover-preview.adjustable .cover-hint { display: block; }

        .cover-adjust {
            display: none;
            margin-top: 16px; padding-top: 14px; border-top: 1px dashed #dbe0e8;
        }
        .cover-adjust.show { display: block; }

        .quality { font-size: 12.5px; margin: 10px 0 0; font-weight: 700; }
        .quality.ok   { color: #166534; }
        .quality.warn { color: #92400e; }
        .quality.busy { color: var(--muted); }

        /* ===== PILIHAN (segmented) ===== */
        .seg { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 16px; }
        .seg input { position: absolute; opacity: 0; pointer-events: none; }
        .seg label {
            margin: 0; padding: 10px 16px; border: 1.5px solid #e2e8f0; border-radius: 999px;
            font-size: 13px; font-weight: 700; color: var(--ink-2); background: #fff; cursor: pointer;
            transition: all .15s;
        }
        .seg label:hover { border-color: #fda4af; transform: translateY(-1px); }
        .seg input:checked + label {
            background: var(--grad); border-color: transparent; color: #fff;
            box-shadow: 0 8px 18px rgba(225, 29, 72, .3);
        }
        .seg input:focus-visible + label { box-shadow: 0 0 0 4px rgba(225,29,72,.25); }

        .cover-panel {
            display: none; margin-top: 16px; padding: 20px; border: 2px dashed #e2e8f0;
            border-radius: 20px; background: rgba(248, 250, 252, .85);
        }
        .cover-panel.show { display: block; }

        .color-row { display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end; }
        .color-field { display: flex; flex-direction: column; gap: 6px; font-size: 13px; font-weight: 700; }
        .color-field input[type=color] {
            width: 68px; height: 46px; padding: 3px; border: 1.5px solid #e2e8f0;
            border-radius: 12px; background: #fff; cursor: pointer;
        }

        .presets { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 16px; }
        .preset {
            width: 40px; height: 40px; border-radius: 50%; border: 3px solid #fff;
            box-shadow: 0 0 0 1.5px #cbd5e1, var(--shadow-sm); cursor: pointer; padding: 0;
            transition: transform .15s, box-shadow .15s;
        }
        .preset:hover { transform: scale(1.14); box-shadow: 0 0 0 2px var(--red), var(--shadow-sm); }

        .tip {
            font-size: 12.5px; color: #92400e; background: #fffbeb; border: 1px solid #fde68a;
            padding: 10px 14px; border-radius: 12px; margin-bottom: 14px; line-height: 1.6;
        }

        /* ===== WARNA TULISAN ===== */
        .text-preview {
            margin-top: 18px; padding: 22px 24px; border-radius: 20px;
            border: 1px solid var(--line); background: #fff; box-shadow: var(--shadow-sm);
        }
        .text-preview .pv-name { font-size: 27px; font-weight: 800; letter-spacing: -.03em; line-height: 1.2; overflow-wrap: anywhere; }
        .text-preview .pv-user { font-size: 15px; font-weight: 700; margin-top: 2px; overflow-wrap: anywhere; }

        .text-colors { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px; }
        .text-colors .box { padding: 18px; border: 2px dashed #e2e8f0; border-radius: 20px; background: rgba(248, 250, 252, .85); }
        .text-colors .box strong { display: block; font-size: 14px; margin-bottom: 12px; }
        .text-colors .row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .text-colors input[type=color] {
            width: 68px; height: 46px; padding: 3px; border: 1.5px solid #e2e8f0;
            border-radius: 12px; background: #fff; cursor: pointer;
        }

        /* ===== EDITOR FOTO PROFIL ===== */
        .avatar-box {
            display: flex; gap: 28px; align-items: center; flex-wrap: wrap;
            margin-top: 18px; padding: 24px; border: 2px dashed #e2e8f0;
            border-radius: 24px; background: rgba(248, 250, 252, .85);
        }

        /* Area kerja persegi. Lingkaran di tengah = bagian yang akan dipakai sebagai foto profil */
        .stage {
            position: relative;
            flex: none;
            width: min(300px, 100%);
            aspect-ratio: 1 / 1;
            border-radius: 24px;
            overflow: hidden;
            background: #f1f5f9;
            box-shadow: 0 0 0 1px #e2e8f0, 0 16px 34px rgba(15, 23, 42, .14);
        }

        /* Foto profil saat ini (sebelum memilih foto baru) */
        .stage .current {
            position: absolute;
            left: 10%; top: 10%;
            width: 80%; height: 80%;
            border-radius: 50%;
            object-fit: cover;
            background: #fff7ed;
            display: grid; place-items: center;
            font-size: 80px;
            box-shadow: 0 0 0 4px #fff, 0 0 0 7px #fda4af;
        }

        .stage canvas {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            display: block;
            cursor: grab;
            touch-action: none;
            outline: none;
        }
        .stage canvas.dragging { cursor: grabbing; }

        /* Bingkai lingkaran: area luar digelapkan */
        .stage .mask {
            display: none;
            position: absolute;
            left: 10%; top: 10%;
            width: 80%; height: 80%;
            border-radius: 50%;
            pointer-events: none;
            border: 2px solid rgba(255,255,255,.95);
            box-shadow: 0 0 0 999px rgba(15, 23, 42, .58);
            background:
                linear-gradient(rgba(255,255,255,.35), rgba(255,255,255,.35)) center/1px 100% no-repeat,
                linear-gradient(rgba(255,255,255,.35), rgba(255,255,255,.35)) center/100% 1px no-repeat;
        }
        .stage.editing .mask { display: block; }

        .avatar-tools { flex: 1; min-width: 220px; }
        .avatar-tools strong { display: block; font-size: 16px; font-weight: 800; margin-bottom: 4px; }
        .avatar-tools .sub { color: var(--muted); font-size: 13px; line-height: 1.65; margin: 0 0 14px; overflow-wrap: anywhere; }

        .sr-only {
            position: absolute; width: 1px; height: 1px; overflow: hidden;
            clip: rect(0 0 0 0); white-space: nowrap;
        }

        .crop-controls { display: none; margin-top: 16px; }
        .crop-controls.show { display: block; }

        .zoom-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
        .zoom-row span { font-size: 18px; color: var(--muted); user-select: none; }

        input[type=range] {
            flex: 1; -webkit-appearance: none; appearance: none; height: 7px; border-radius: 999px;
            background: #e2e8f0; padding: 0; border: 0; box-shadow: none;
        }
        input[type=range]:focus { box-shadow: none; background: #e2e8f0; }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none; width: 24px; height: 24px; border-radius: 50%;
            background: var(--grad); border: 3px solid #fff; box-shadow: 0 3px 10px rgba(225,29,72,.4); cursor: pointer;
        }
        input[type=range]::-moz-range-thumb {
            width: 18px; height: 18px; border-radius: 50%;
            background: var(--red); border: 3px solid #fff; box-shadow: 0 3px 10px rgba(225,29,72,.4); cursor: pointer;
        }

        /* =====================================================================
           BORDER FOTO PROFIL (efek animasi) - sama dengan halaman profil
           ===================================================================== */
        .avatar-wrap {
            --bd-glow: rgba(225, 29, 72, .32);
            --bd-ring: transparent;
            position: relative;
            isolation: isolate;
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 14px 30px var(--bd-glow);
        }
        .avatar-wrap::before {
            content: ""; position: absolute; inset: 0; border-radius: 50%;
            z-index: -1; background: var(--grad);
        }
        .avatar-wrap::after {
            content: ""; position: absolute; inset: -4px; border-radius: 50%;
            z-index: -2; pointer-events: none; opacity: 0;
            border: 2px solid var(--bd-ring);
        }

        @keyframes bdSpin   { to { transform: rotate(360deg); } }
        @keyframes bdRipple {
            0%   { transform: scale(1);   opacity: .85; }
            100% { transform: scale(1.3); opacity: 0; }
        }
        @keyframes bdGlow {
            from { box-shadow: 0 0 8px 0 var(--bd-glow); }
            to   { box-shadow: 0 0 30px 6px var(--bd-glow); }
        }
        @keyframes bdFlicker {
            0%, 100% { box-shadow: 0 0 14px 2px var(--bd-glow); }
            30%      { box-shadow: 0 0 28px 7px var(--bd-glow); }
            55%      { box-shadow: 0 0 10px 1px var(--bd-glow); }
            78%      { box-shadow: 0 0 24px 5px var(--bd-glow); }
        }
        @keyframes bdBeat {
            0%, 100% { transform: scale(1); }
            14%      { transform: scale(1.06); }
            28%      { transform: scale(1); }
            42%      { transform: scale(1.09); }
            70%      { transform: scale(1); }
        }

        .bd-none { --bd-glow: rgba(15, 23, 42, .18); }
        .bd-none::before { background: #fff; }

        .bd-emas { --bd-glow: rgba(245, 158, 11, .6); --bd-ring: #fbbf24; animation: bdGlow 2.4s ease-in-out infinite alternate; }
        .bd-emas::before { background: conic-gradient(from 0deg, #b45309, #fde68a, #f59e0b, #fff7ad, #b45309); animation: bdSpin 4s linear infinite; }
        .bd-emas::after { animation: bdRipple 2.2s ease-out infinite; }

        .bd-pelangi { --bd-glow: rgba(139, 92, 246, .4); }
        .bd-pelangi::before { background: conic-gradient(#ef4444, #f59e0b, #facc15, #22c55e, #06b6d4, #3b82f6, #a855f7, #ef4444); animation: bdSpin 3s linear infinite; }

        .bd-neon { --bd-glow: rgba(34, 211, 238, .75); --bd-ring: #22d3ee; animation: bdGlow 1.4s ease-in-out infinite alternate; }
        .bd-neon::before { background: conic-gradient(#22d3ee, #a855f7, #22d3ee, #a855f7, #22d3ee); animation: bdSpin 6s linear infinite; }
        .bd-neon::after { animation: bdRipple 1.8s ease-out infinite; }

        .bd-api { --bd-glow: rgba(249, 115, 22, .75); --bd-ring: #fb923c; animation: bdFlicker 1.1s ease-in-out infinite; }
        .bd-api::before { background: conic-gradient(#ef4444, #f97316, #facc15, #f97316, #ef4444); animation: bdSpin 2.4s linear infinite; }
        .bd-api::after { animation: bdRipple 1.5s ease-out infinite; }

        .bd-es { --bd-glow: rgba(56, 189, 248, .6); --bd-ring: #7dd3fc; animation: bdGlow 3s ease-in-out infinite alternate; }
        .bd-es::before { background: conic-gradient(#e0f2fe, #38bdf8, #ffffff, #7dd3fc, #e0f2fe); animation: bdSpin 5s linear infinite; }
        .bd-es::after { animation: bdRipple 2.6s ease-out infinite; }

        .bd-galaksi { --bd-glow: rgba(124, 58, 237, .65); --bd-ring: #a78bfa; animation: bdGlow 2s ease-in-out infinite alternate; }
        .bd-galaksi::before { background: conic-gradient(#1e1b4b, #7c3aed, #ec4899, #3b82f6, #1e1b4b); animation: bdSpin 4s linear infinite; }
        .bd-galaksi::after { animation: bdRipple 2.4s ease-out infinite; }

        .bd-hati { --bd-glow: rgba(244, 63, 94, .6); --bd-ring: #fb7185; animation: bdBeat 1.4s ease-in-out infinite; }
        .bd-hati::before { background: linear-gradient(135deg, #f472b6, #e11d48 55%, #fb7185); }
        .bd-hati::after { animation: bdRipple 1.4s ease-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            .avatar-wrap,
            .avatar-wrap::before,
            .avatar-wrap::after { animation: none !important; }
        }

        /* Ukuran khusus untuk pratinjau & kotak pilihan di halaman edit */
        .avatar-wrap.lg { padding: 6px; }
        .avatar-wrap.sm { padding: 3px; box-shadow: none; }
        .avatar-wrap.sm::after { inset: -3px; border-width: 1.5px; }

        .bd-inner {
            display: grid; place-items: center; overflow: hidden;
            border-radius: 50%; object-fit: cover;
            background: linear-gradient(135deg, #fff1f2, #ffedd5);
            border: 4px solid #fff;
        }
        .avatar-wrap.lg .bd-inner { width: 120px; height: 120px; font-size: 52px; }
        .avatar-wrap.sm .bd-inner { width: 54px; height: 54px; font-size: 22px; border-width: 3px; }

        .border-preview {
            display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 12px;
            margin-top: 18px; padding: 30px 20px;
            border: 1px solid var(--line); border-radius: 22px;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 241, 242, .9), transparent 55%),
                radial-gradient(circle at 80% 80%, rgba(255, 247, 237, .9), transparent 55%),
                #fff;
            box-shadow: var(--shadow-sm);
        }
        .border-preview .bp-name { font-size: 14px; font-weight: 800; color: var(--ink-2); }

        .border-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(118px, 1fr));
            gap: 12px; margin-top: 18px;
        }
        .border-grid input { position: absolute; opacity: 0; pointer-events: none; }
        .border-grid label.bd-tile {
            margin: 0; display: flex; flex-direction: column; align-items: center; gap: 12px;
            padding: 18px 8px 14px; text-align: center; cursor: pointer;
            border: 2px solid #e2e8f0; border-radius: 20px; background: #fff;
            transition: border-color .15s, transform .15s, box-shadow .15s;
        }
        .border-grid label.bd-tile:hover { border-color: #fda4af; transform: translateY(-2px); }
        .border-grid input:checked + label.bd-tile {
            border-color: var(--red); background: var(--red-soft);
            box-shadow: 0 10px 22px rgba(225, 29, 72, .2);
        }
        .border-grid input:focus-visible + label.bd-tile { box-shadow: 0 0 0 4px rgba(225,29,72,.25); }
        .bd-tile .t-name { font-size: 13px; font-weight: 800; color: var(--ink); }
        .bd-tile .t-sub { display: block; font-size: 11.5px; font-weight: 600; color: var(--muted); margin-top: 1px; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 12px 20px; border: 1px solid rgba(15, 23, 42, .1); border-radius: 14px;
            background: #fff; color: var(--ink); font-size: 14px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            transition: transform .15s, box-shadow .15s, background .15s;
        }
        .btn:hover { background: #f8fafc; transform: translateY(-2px); box-shadow: var(--shadow-sm); }
        .btn.sm { padding: 9px 14px; font-size: 13px; border-radius: 12px; }

        .btn:focus-visible { outline: 3px solid rgba(225, 29, 72, .5); outline-offset: 2px; }

        .primary {
            background: var(--grad);
            border-color: transparent; color: #fff; box-shadow: 0 10px 22px rgba(225, 29, 72, .32);
        }
        .primary:hover { background: var(--grad); filter: brightness(1.06); box-shadow: 0 14px 28px rgba(225, 29, 72, .4); }
        .primary:disabled { opacity: .7; cursor: wait; transform: none; }

        .btn-row { display: flex; gap: 8px; flex-wrap: wrap; }

        /* Bar aksi melayang di bawah layar */
        .actions {
            position: sticky; bottom: 14px; z-index: 20;
            display: flex; gap: 10px; flex-wrap: wrap; align-items: center; justify-content: flex-end;
            margin-top: 6px; padding: 12px 14px;
            background: rgba(255, 255, 255, .88);
            -webkit-backdrop-filter: blur(16px) saturate(160%); backdrop-filter: blur(16px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, .8); border-radius: 20px;
            box-shadow: 0 12px 34px rgba(15, 23, 42, .16);
        }
        .actions .note { margin-right: auto; font-size: 13px; color: var(--muted); font-weight: 600; padding-left: 6px; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .container { padding: 14px 12px 30px; }
            .hero { padding: 22px 20px; gap: 14px; border-radius: 22px; }
            .hero .avatar { width: 68px; height: 68px; font-size: 30px; }
            .hero h1 { font-size: 22px; }
            .section { padding: 20px 16px 24px; border-radius: 22px; }
            .grid { grid-template-columns: 1fr; }
            .text-colors { grid-template-columns: 1fr; }
            .avatar-box { justify-content: center; padding: 18px; }
            .section-desc { margin-left: 0; margin-top: 8px; }
            .actions { padding: 10px; }
            .actions .note { display: none; }
            .actions .btn { flex: 1; }
            .cover-preview { height: 140px; }
            .border-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
        }
    </style>
</head>

<body>
@php
    // ----- Sampul -----
    $coverType   = $user->cover_type ?? 'default';
    $coverChoice = in_array($coverType, ['image', 'video']) ? 'media' : $coverType;
    $coverChoice = old('cover_type', $coverChoice);
    $coverColor1 = old('cover_color', $user->cover_color ?: '#dc2626');
    $coverColor2 = old('cover_color2', $user->cover_color2 ?: '#f97316');
    $coverUrl    = $user->cover_path ? asset('storage/'.$user->cover_path) : '';

    // Posisi & zoom sampul foto/video (persen 0-100, zoom 1-3)
    $coverPosX = max(0, min(100, (float) old('cover_pos_x', $user->cover_pos_x ?? 50)));
    $coverPosY = max(0, min(100, (float) old('cover_pos_y', $user->cover_pos_y ?? 50)));
    $coverZoom = max(1, min(3, (float) old('cover_zoom', $user->cover_zoom ?? 1)));

    // ----- Warna tulisan -----
    $nameColor = old('name_color', $user->name_color);
    $userColor = old('username_color', $user->username_color);
    $displayUsername = $user->username ?: \Illuminate\Support\Str::slug($user->name, '_');

    // ----- Background halaman -----
    $bgType    = old('bg_type', $user->bg_type ?? 'default');
    $bgColor1  = old('bg_color', $user->bg_color ?: '#f6f7fb');
    $bgColor2  = old('bg_color2', $user->bg_color2 ?: '#fde68a');
    $bgUrl     = $user->bg_path ? asset('storage/'.$user->bg_path) : '';

    // Gambar latar salju (taruh di public/images/bg/salju-bg.png)
    $bgSnowUrl = asset('images/bg/salju-bg.png');

    // ----- Border foto profil -----
    // [ikon, nama, keterangan efek]
    $borders = [
        'default' => ['🔴', 'Default',  'Gradien merah'],
        'none'    => ['⚪', 'Polos',    'Tanpa efek'],
        'emas'    => ['👑', 'Emas',     'Berkilau & berdenyut'],
        'pelangi' => ['🌈', 'Pelangi',  'Warna berputar'],
        'neon'    => ['💠', 'Neon',     'Cahaya menyala'],
        'api'     => ['🔥', 'Api',      'Menyala berkobar'],
        'es'      => ['❄️', 'Es',       'Kilau dingin'],
        'galaksi' => ['🌌', 'Galaksi',  'Cahaya kosmik'],
        'hati'    => ['💗', 'Hati',     'Detak jantung'],
    ];

    $avatarBorder = old('avatar_border', $user->avatar_border ?: 'default');
    if (!array_key_exists($avatarBorder, $borders)) {
        $avatarBorder = 'default';
    }

    $avatarSrc = $user->avatar_path ? asset('storage/'.$user->avatar_path) : null;
@endphp

<main class="container">

    <a class="back anim" href="{{ route('profile.show') }}">← Kembali ke Profil</a>

    <header class="hero anim d1">
        @if($user->avatar_path)
            <img class="avatar" src="{{ asset('storage/'.$user->avatar_path) }}" alt="Foto profil">
        @else
            <div class="avatar">🧳</div>
        @endif

        <div>
            <h1>Edit Profil</h1>
            <p>Perbarui informasi akun penjelajahmu.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="success anim">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error anim">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="profileForm" class="anim d2" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- SAMPUL PROFIL --}}
        <div class="section">
            <h2 class="title"><span class="ico">🎨</span> Sampul profil</h2>
            <p class="section-desc">Ubah latar bagian atas profilmu: warna, foto, atau video.</p>

            <div class="cover-preview" id="coverPreview">
                <span class="cover-hint" id="coverHint">✋ Seret untuk mengatur posisi</span>
                <span class="cover-badge" id="coverBadge">Pratinjau sampul</span>
            </div>
            <p class="quality" id="coverQuality" hidden></p>

            <div class="seg">
                <input type="radio" name="cover_type" id="ct_default" value="default" {{ $coverChoice === 'default' ? 'checked' : '' }}>
                <label for="ct_default">🔴 Default</label>

                <input type="radio" name="cover_type" id="ct_gradient" value="gradient" {{ $coverChoice === 'gradient' ? 'checked' : '' }}>
                <label for="ct_gradient">🌈 Warna / Gradien</label>

                <input type="radio" name="cover_type" id="ct_media" value="media" {{ $coverChoice === 'media' ? 'checked' : '' }}>
                <label for="ct_media">🖼️ Foto / Video</label>
            </div>

            <div class="cover-panel" id="panel-gradient">
                <div class="color-row">
                    <div class="color-field">
                        Warna awal
                        <input type="color" id="cover_color" name="cover_color" value="{{ $coverColor1 }}">
                    </div>
                    <div class="color-field">
                        Warna akhir
                        <input type="color" id="cover_color2" name="cover_color2" value="{{ $coverColor2 }}">
                    </div>
                </div>

                <div class="presets" id="presets">
                    <button type="button" class="preset" data-c1="#dc2626" data-c2="#f97316" style="background:linear-gradient(135deg,#dc2626,#f97316)" title="Merah-Oranye"></button>
                    <button type="button" class="preset" data-c1="#0ea5e9" data-c2="#6366f1" style="background:linear-gradient(135deg,#0ea5e9,#6366f1)" title="Laut"></button>
                    <button type="button" class="preset" data-c1="#10b981" data-c2="#065f46" style="background:linear-gradient(135deg,#10b981,#065f46)" title="Hutan"></button>
                    <button type="button" class="preset" data-c1="#f59e0b" data-c2="#ef4444" style="background:linear-gradient(135deg,#f59e0b,#ef4444)" title="Senja"></button>
                    <button type="button" class="preset" data-c1="#ec4899" data-c2="#8b5cf6" style="background:linear-gradient(135deg,#ec4899,#8b5cf6)" title="Magenta"></button>
                    <button type="button" class="preset" data-c1="#111827" data-c2="#4b5563" style="background:linear-gradient(135deg,#111827,#4b5563)" title="Malam"></button>
                </div>
                <p class="hint">Pilih dua warna sendiri, atau klik salah satu preset di atas.</p>
            </div>

            <div class="cover-panel" id="panel-media">
                <input class="sr-only" id="cover_file" type="file" name="cover_file"
                       accept=".jpg,.jpeg,.png,.webp,.mp4,.webm,image/jpeg,image/png,image/webp,video/mp4,video/webm">

                <div class="tip">💡 Foto: JPG/PNG/WEBP (ukuran berapa pun, otomatis dioptimalkan agar tetap tajam). Video: MP4/WEBM, maksimal 20 MB, diputar otomatis tanpa suara (loop). Untuk hasil paling tajam gunakan foto/video minimal 1920 px lebarnya.</div>

                <div class="btn-row">
                    <label class="btn sm" for="cover_file" style="margin:0">📁 Pilih foto / video</label>
                </div>
                <p class="hint" id="coverFileName">
                    @if($coverUrl) Sampul saat ini dipakai. Pilih file baru untuk menggantinya. @else Belum ada file dipilih. @endif
                </p>

                {{-- ATUR POSISI & ZOOM SAMPUL --}}
                <div class="cover-adjust" id="coverAdjust">
                    <div class="tip">☝️ Seret pratinjau sampul di atas ke mana saja untuk menggeser foto/video. Geser slider atau scroll mouse di atas pratinjau untuk zoom. Posisi ini ikut tersimpan dan dipakai di halaman profilmu.</div>

                    <div class="zoom-row">
                        <span>－</span>
                        <input type="range" id="coverZoom" min="1" max="3" step="0.01" value="{{ $coverZoom }}" aria-label="Zoom sampul">
                        <span>＋</span>
                    </div>

                    <div class="btn-row">
                        <button type="button" class="btn sm" id="coverCenter">◎ Tengahkan</button>
                        <button type="button" class="btn sm" id="coverReset">↺ Atur ulang posisi</button>
                    </div>
                </div>
            </div>

            {{-- Nilai posisi yang dikirim ke server --}}
            <input type="hidden" name="cover_pos_x" id="cover_pos_x" value="{{ $coverPosX }}">
            <input type="hidden" name="cover_pos_y" id="cover_pos_y" value="{{ $coverPosY }}">
            <input type="hidden" name="cover_zoom" id="cover_zoom" value="{{ $coverZoom }}">

            @error('cover_file')<span class="field-error">{{ $message }}</span>@enderror
            @error('cover_type')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- FOTO PROFIL --}}
        <div class="section">
            <h2 class="title"><span class="ico">📷</span> Foto profil</h2>
            <p class="section-desc">Pilih foto, lalu geser, zoom, atau putar sampai pas di dalam lingkaran.</p>

            <div class="avatar-box">
                <div class="stage" id="stage">
                    @if($user->avatar_path)
                        <img class="current" id="currentAvatar" src="{{ asset('storage/'.$user->avatar_path) }}" alt="Foto profil saat ini">
                    @else
                        <span class="current" id="currentAvatar">🧳</span>
                    @endif

                    <canvas id="cropCanvas" tabindex="0" hidden aria-label="Area atur foto profil"></canvas>
                    <div class="mask" id="cropMask"></div>
                </div>

                <div class="avatar-tools">
                    <strong id="avatarTitle">Foto saat ini</strong>
                    <p class="sub" id="avatarSub">Format JPG, PNG, atau WEBP, maksimal 2 MB. Kamu juga bisa menjatuhkan (drag &amp; drop) foto ke kotak di samping.</p>

                    <input class="sr-only" id="avatar" type="file" name="avatar"
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                    <div class="btn-row">
                        <label class="btn sm" for="avatar" style="margin:0" id="pickLabel">📁 Pilih foto</label>
                    </div>

                    <div class="crop-controls" id="cropControls">
                        <div class="tip">☝️ Seret foto ke mana saja untuk memindahkan. Geser slider, scroll mouse, atau cubit layar untuk zoom. Panah keyboard juga bisa dipakai.</div>

                        <div class="zoom-row">
                            <span>－</span>
                            <input type="range" id="zoom" min="0.5" max="4" step="0.01" value="1" aria-label="Zoom">
                            <span>＋</span>
                        </div>

                        <div class="btn-row" style="margin-bottom:8px">
                            <button type="button" class="btn sm" id="fitCrop">⤢ Pas ukuran</button>
                            <button type="button" class="btn sm" id="fillCrop">⬤ Penuhi lingkaran</button>
                        </div>

                        <div class="btn-row" style="margin-bottom:8px">
                            <button type="button" class="btn sm" id="rotLeft">⟲ Putar kiri</button>
                            <button type="button" class="btn sm" id="rotRight">⟳ Putar kanan</button>
                        </div>

                        <div class="btn-row">
                            <button type="button" class="btn sm" id="resetCrop">↺ Atur ulang</button>
                            <button type="button" class="btn sm" id="cancelCrop">✕ Batalkan foto</button>
                        </div>
                    </div>
                </div>
            </div>
            @error('avatar')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- BORDER FOTO PROFIL --}}
        <div class="section">
            <h2 class="title"><span class="ico">💫</span> Border foto profil</h2>
            <p class="section-desc">Pilih bingkai beranimasi untuk foto profilmu. Hasilnya tampil di halaman profil.</p>

            <div class="border-preview">
                <div class="avatar-wrap lg bd-{{ $avatarBorder }}" id="borderPreview">
                    @if($avatarSrc)
                        <img class="bd-inner" id="borderPreviewImg" src="{{ $avatarSrc }}" alt="Pratinjau border">
                    @else
                        <div class="bd-inner" id="borderPreviewImg">🧳</div>
                    @endif
                </div>
                <div class="bp-name" id="borderPreviewName">
                    {{ $borders[$avatarBorder][0] }} {{ $borders[$avatarBorder][1] }}
                </div>
            </div>

            <div class="border-grid" id="borderGrid">
                @foreach($borders as $key => $b)
                    <input type="radio" name="avatar_border" id="abd_{{ $key }}" value="{{ $key }}"
                           data-label="{{ $b[0] }} {{ $b[1] }}"
                           {{ $avatarBorder === $key ? 'checked' : '' }}>
                    <label class="bd-tile" for="abd_{{ $key }}">
                        <div class="avatar-wrap sm bd-{{ $key }}">
                            <div class="bd-inner">{{ $b[0] }}</div>
                        </div>
                        <div>
                            <span class="t-name">{{ $b[1] }}</span>
                            <span class="t-sub">{{ $b[2] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            @error('avatar_border')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- INFORMASI PRIBADI --}}
        <div class="section">
            <h2 class="title"><span class="ico">♙</span> Informasi pribadi</h2>
            <p class="section-desc">Data ini tampil di halaman profilmu.</p>

            <div class="grid">
                <div>
                    <label for="name">Nama penjelajah</label>
                    <input id="name" name="name" class="@error('name') is-invalid @enderror"
                           value="{{ old('name',$user->name) }}" required maxlength="255">
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="username">Username</label>
                    <div class="input-prefix">
                        <span>@</span>
                        <input id="username" name="username" class="@error('username') is-invalid @enderror"
                               value="{{ old('username',$user->username ?: \Illuminate\Support\Str::slug($user->name,'_')) }}"
                               required maxlength="30">
                    </div>
                    @error('username')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="email">Alamat email</label>
                    <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror"
                           value="{{ old('email',$user->email) }}" required>
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="domicile">Domisili</label>
                    <input id="domicile" name="domicile" class="@error('domicile') is-invalid @enderror"
                           value="{{ old('domicile',$user->domicile) }}"
                           placeholder="Contoh: Palembang, Sumatera Selatan" maxlength="100">
                    @error('domicile')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="full">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" maxlength="500"
                              class="@error('bio') is-invalid @enderror"
                              placeholder="Ceritakan sedikit tentang dirimu">{{ old('bio',$user->bio) }}</textarea>
                    <div class="counter"><span id="bioCount">0</span>/500</div>
                    @error('bio')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- WARNA TULISAN --}}
        <div class="section">
            <h2 class="title"><span class="ico">🔤</span> Warna tulisan</h2>
            <p class="section-desc">Ubah warna nama dan username di halaman profilmu.</p>

            <div class="text-preview">
                <div class="pv-name" id="pvName">{{ old('name', $user->name) }}</div>
                <div class="pv-user" id="pvUser">@ {{ $displayUsername }}</div>
            </div>

            <div class="text-colors">
                <div class="box">
                    <strong>Warna nama</strong>
                    <div class="row">
                        <input type="color" id="nameColorPick" value="{{ $nameColor ?: '#1f2937' }}" aria-label="Warna nama">
                        <input type="hidden" id="name_color" name="name_color" value="{{ $nameColor }}">
                        <button type="button" class="btn sm" id="nameColorReset">↺ Default</button>
                    </div>
                </div>

                <div class="box">
                    <strong>Warna username</strong>
                    <div class="row">
                        <input type="color" id="userColorPick" value="{{ $userColor ?: '#dc2626' }}" aria-label="Warna username">
                        <input type="hidden" id="username_color" name="username_color" value="{{ $userColor }}">
                        <button type="button" class="btn sm" id="userColorReset">↺ Default</button>
                    </div>
                </div>
            </div>
            @error('name_color')<span class="field-error">{{ $message }}</span>@enderror
            @error('username_color')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- BACKGROUND HALAMAN PROFIL --}}
        <div class="section">
            <h2 class="title"><span class="ico">🖌️</span> Background profil</h2>
            <p class="section-desc">Ubah latar belakang halaman profilmu (area di belakang kartu putih).</p>

            <div class="cover-preview" id="bgPreview" style="background:#f6f7fb">
                <div class="snow-side left"></div>
                <div class="snow-side right"></div>
                <div class="snow-frame top" style="background-image: url('{{ $bgSnowUrl }}')"></div>
                <div class="snow-frame bottom" style="background-image: url('{{ $bgSnowUrl }}')"></div>
                <canvas class="snow-canvas" id="bgSnow"></canvas>
                <span class="cover-badge" id="bgBadge">Pratinjau background</span>
            </div>
            <p class="quality" id="bgQuality" hidden></p>

            <div class="seg">
                <input type="radio" name="bg_type" id="bg_default" value="default" {{ $bgType === 'default' ? 'checked' : '' }}>
                <label for="bg_default">⚪ Default</label>

                <input type="radio" name="bg_type" id="bg_color_r" value="color" {{ $bgType === 'color' ? 'checked' : '' }}>
                <label for="bg_color_r">🎨 Warna polos</label>

                <input type="radio" name="bg_type" id="bg_gradient_r" value="gradient" {{ $bgType === 'gradient' ? 'checked' : '' }}>
                <label for="bg_gradient_r">🌈 Gradien</label>

                <input type="radio" name="bg_type" id="bg_image_r" value="image" {{ $bgType === 'image' ? 'checked' : '' }}>
                <label for="bg_image_r">🖼️ Foto</label>

                <input type="radio" name="bg_type" id="bg_salju_r" value="salju" {{ $bgType === 'salju' ? 'checked' : '' }}>
                <label for="bg_salju_r">❄️ Salju</label>
            </div>

            {{-- Warna polos --}}
            <div class="cover-panel" id="bgpanel-color">
                <div class="color-row">
                    <div class="color-field">
                        Warna background
                        <input type="color" id="bg_color_a" value="{{ $bgColor1 }}">
                    </div>
                </div>
                <div class="presets" id="bgPresetsColor">
                    <button type="button" class="preset" data-c1="#f6f7fb" style="background:#f6f7fb" title="Abu terang"></button>
                    <button type="button" class="preset" data-c1="#fef2f2" style="background:#fef2f2" title="Merah muda"></button>
                    <button type="button" class="preset" data-c1="#ecfdf5" style="background:#ecfdf5" title="Hijau muda"></button>
                    <button type="button" class="preset" data-c1="#eff6ff" style="background:#eff6ff" title="Biru muda"></button>
                    <button type="button" class="preset" data-c1="#fffbeb" style="background:#fffbeb" title="Krem"></button>
                    <button type="button" class="preset" data-c1="#111827" style="background:#111827" title="Gelap"></button>
                </div>
            </div>

            {{-- Gradien --}}
            <div class="cover-panel" id="bgpanel-gradient">
                <div class="color-row">
                    <div class="color-field">
                        Warna awal
                        <input type="color" id="bg_color_b" value="{{ $bgColor1 }}">
                    </div>
                    <div class="color-field">
                        Warna akhir
                        <input type="color" id="bg_color2_b" value="{{ $bgColor2 }}">
                    </div>
                </div>
                <div class="presets" id="bgPresetsGrad">
                    <button type="button" class="preset" data-c1="#fecaca" data-c2="#fed7aa" style="background:linear-gradient(135deg,#fecaca,#fed7aa)" title="Senja lembut"></button>
                    <button type="button" class="preset" data-c1="#bae6fd" data-c2="#c7d2fe" style="background:linear-gradient(135deg,#bae6fd,#c7d2fe)" title="Langit"></button>
                    <button type="button" class="preset" data-c1="#bbf7d0" data-c2="#a7f3d0" style="background:linear-gradient(135deg,#bbf7d0,#a7f3d0)" title="Daun"></button>
                    <button type="button" class="preset" data-c1="#fbcfe8" data-c2="#ddd6fe" style="background:linear-gradient(135deg,#fbcfe8,#ddd6fe)" title="Permen"></button>
                    <button type="button" class="preset" data-c1="#0f172a" data-c2="#334155" style="background:linear-gradient(135deg,#0f172a,#334155)" title="Malam"></button>
                    <button type="button" class="preset" data-c1="#dc2626" data-c2="#f97316" style="background:linear-gradient(135deg,#dc2626,#f97316)" title="Merah-Oranye"></button>
                </div>
            </div>

            {{-- Foto --}}
            <div class="cover-panel" id="bgpanel-image">
                <input class="sr-only" id="bg_file" type="file" name="bg_file"
                       accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <div class="tip">💡 Foto JPG/PNG/WEBP (ukuran berapa pun, otomatis dioptimalkan hingga maksimal 5 MB tanpa mengurangi ketajaman yang terlihat). Pilih foto yang tidak terlalu ramai agar kartu profil tetap nyaman dibaca.</div>
                <div class="btn-row">
                    <label class="btn sm" for="bg_file" style="margin:0">📁 Pilih foto background</label>
                </div>
                <p class="hint" id="bgFileName">
                    @if($bgUrl) Background saat ini dipakai. Pilih foto baru untuk menggantinya. @else Belum ada foto dipilih. @endif
                </p>
            </div>

            {{-- Salju --}}
            <div class="cover-panel" id="bgpanel-salju">
                <div class="tip" style="margin-bottom:0">❄️ Hiasan salju di pinggir layar (atas, bawah, dan sisi) dengan latar biru es lembut, ditambah butiran salju yang jatuh pelan di halaman profilmu. Bagian tengah tetap polos agar kartu profil nyaman dibaca.</div>
            </div>

            {{-- Nilai warna yang benar-benar dikirim --}}
            <input type="hidden" name="bg_color" id="bg_color" value="{{ $bgColor1 }}">
            <input type="hidden" name="bg_color2" id="bg_color2" value="{{ $bgColor2 }}">

            @error('bg_file')<span class="field-error">{{ $message }}</span>@enderror
            @error('bg_type')<span class="field-error">{{ $message }}</span>@enderror
        </div>

        {{-- KEAMANAN --}}
        <div class="section">
            <h2 class="title"><span class="ico">🔒</span> Keamanan akun</h2>
            <p class="section-desc">Kosongkan jika tidak ingin mengganti password.</p>

            <div class="grid">
                <div>
                    <label for="password">Password baru</label>
                    <div class="pw">
                        <input id="password" type="password" name="password" minlength="8"
                               class="@error('password') is-invalid @enderror"
                               autocomplete="new-password" placeholder="Minimal 8 karakter">
                        <button type="button" data-toggle="password">Lihat</button>
                    </div>
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="password_confirmation">Konfirmasi password baru</label>
                    <div class="pw">
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               minlength="8" autocomplete="new-password" placeholder="Ulangi password baru">
                        <button type="button" data-toggle="password_confirmation">Lihat</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="actions">
            <span class="note">Jangan lupa simpan perubahanmu ✨</span>
            <a class="btn" href="{{ route('profile.show') }}">Batal</a>
            <button class="btn primary" id="saveBtn" type="submit">💾 Simpan Perubahan</button>
        </div>
    </form>

</main>

{{-- Mesin partikel salju (public/js/snow-fx.js) --}}
<script src="{{ asset('js/snow-fx.js') }}"></script>

<script>
(function () {
    /* ===== Counter bio ===== */
    var bio = document.getElementById('bio');
    var bioCount = document.getElementById('bioCount');
    function updateCount() { bioCount.textContent = bio.value.length; }
    bio.addEventListener('input', updateCount);
    updateCount();

    /* ===== Tombol lihat password ===== */
    document.querySelectorAll('[data-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.toggle);
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Sembunyikan' : 'Lihat';
        });
    });

    /* =====================================================================
     * HD: pengolah gambar / pembaca info video (dipakai sampul & background)
     *
     * Aturan:
     *  - Gambar yang sudah cukup tajam (1920-3840 px) & muat di batas ukuran
     *    dikirim APA ADANYA (tanpa kompresi ulang = tanpa penurunan kualitas).
     *  - Gambar terlalu besar -> diperkecil bertahap (halving) agar tidak
     *    bergerigi/buram, lalu disimpan kualitas tinggi.
     *  - Gambar kecil -> diperbesar bertahap (smoothing "high") sampai lebar
     *    1920 px, supaya browser tidak memperbesar kasar saat tampil.
     *    (Detail yang tidak ada di foto asli tidak bisa dibuat; pakai foto
     *    beresolusi tinggi untuk hasil terbaik.)
     * ===================================================================== */
    var HD = (function () {
        var MIN_W = 1920;          // lebar minimum target
        var MAX_SIDE = 3840;       // sisi terpanjang maksimum (4K)
        var MAX_UPSCALE = 3;       // batas pembesaran
        var MAX_PIXELS = 16e6;     // batas piksel hasil pembesaran

        function loadImage(file) {
            return new Promise(function (resolve, reject) {
                var url = URL.createObjectURL(file);
                var im = new Image();
                im.onload = function () { URL.revokeObjectURL(url); resolve(im); };
                im.onerror = function () { URL.revokeObjectURL(url); reject(new Error('load')); };
                im.src = url;
            });
        }

        // Ubah ukuran bertahap supaya hasil tetap tajam
        function resizeSteps(src, sw, sh, tw) {
            var cur = src, cw = sw, ch = sh, guard = 0;
            if (tw === sw) {
                var c0 = document.createElement('canvas');
                c0.width = sw; c0.height = sh;
                c0.getContext('2d').drawImage(src, 0, 0);
                return c0;
            }
            while (guard++ < 12) {
                var nw = tw < cw ? Math.max(tw, Math.round(cw / 2))
                                 : Math.min(tw, Math.round(cw * 1.5));
                var nh = Math.max(1, Math.round(nw * sh / sw));
                var c = document.createElement('canvas');
                c.width = nw; c.height = nh;
                var x = c.getContext('2d');
                x.imageSmoothingEnabled = true;
                x.imageSmoothingQuality = 'high';
                x.drawImage(cur, 0, 0, cw, ch, 0, 0, nw, nh);
                cur = c; cw = nw; ch = nh;
                if (cw === tw) break;
            }
            return cur;
        }

        function toBlob(canvas, type, q) {
            return new Promise(function (resolve) { canvas.toBlob(resolve, type, q); });
        }

        function baseName(name) {
            return (name || 'gambar').replace(/\.[^.]+$/, '');
        }

        // Simpan sebagai WEBP kualitas tinggi (fallback JPEG), turunkan kualitas
        // pelan-pelan hanya jika melebihi batas ukuran.
        async function encode(canvas, name, limitBytes) {
            var useWebp = true;
            var test = await toBlob(canvas, 'image/webp', 0.95);
            if (!test || test.type !== 'image/webp') useWebp = false;

            var type = useWebp ? 'image/webp' : 'image/jpeg';
            var ext = useWebp ? '.webp' : '.jpg';
            var work = canvas;

            if (!useWebp) {
                // JPEG tidak mendukung transparansi -> isi putih
                work = document.createElement('canvas');
                work.width = canvas.width; work.height = canvas.height;
                var wx = work.getContext('2d');
                wx.fillStyle = '#fff';
                wx.fillRect(0, 0, work.width, work.height);
                wx.drawImage(canvas, 0, 0);
            }

            var qualities = [0.95, 0.92, 0.88, 0.84, 0.8, 0.75, 0.7];
            var blob = null;
            for (var i = 0; i < qualities.length; i++) {
                blob = await toBlob(work, type, qualities[i]);
                if (blob && blob.size <= limitBytes) break;
            }

            // Masih terlalu besar -> kecilkan sedikit resolusinya
            var tries = 0;
            while (blob && blob.size > limitBytes && work.width > 1280 && tries++ < 6) {
                var tw = Math.round(work.width * 0.85);
                work = resizeSteps(work, work.width, work.height, tw);
                blob = await toBlob(work, type, 0.85);
            }

            if (!blob) return null;
            return {
                file: new File([blob], baseName(name) + ext, { type: type }),
                w: work.width, h: work.height
            };
        }

        /**
         * Olah gambar. Mengembalikan {file, w, h, changed}
         */
        async function processImage(file, limitMB) {
            var limit = limitMB * 1024 * 1024;
            var im = await loadImage(file);
            var sw = im.naturalWidth, sh = im.naturalHeight;
            var longest = Math.max(sw, sh);

            var tw = sw;
            if (longest > MAX_SIDE) {
                tw = Math.round(sw * (MAX_SIDE / longest));          // perkecil
            } else if (sw < MIN_W) {
                var f = Math.min(MAX_UPSCALE, MIN_W / sw);           // perbesar
                var cand = Math.round(sw * f);
                var ch = Math.round(cand * sh / sw);
                if (cand * ch <= MAX_PIXELS) tw = cand;
            }

            // Sudah ideal & muat -> kirim file asli tanpa diubah
            if (tw === sw && file.size <= limit) {
                return { file: file, w: sw, h: sh, changed: false, srcW: sw, srcH: sh };
            }

            var canvas = resizeSteps(im, sw, sh, tw);
            var res = await encode(canvas, file.name, limit);
            if (!res) throw new Error('encode');
            res.changed = true;
            res.srcW = sw; res.srcH = sh;
            return res;
        }

        // Baca resolusi video
        function videoInfo(file) {
            return new Promise(function (resolve) {
                var v = document.createElement('video');
                var url = URL.createObjectURL(file);
                var done = false;
                function finish(w, h) {
                    if (done) return; done = true;
                    URL.revokeObjectURL(url);
                    resolve({ w: w, h: h });
                }
                v.preload = 'metadata';
                v.muted = true;
                v.onloadedmetadata = function () { finish(v.videoWidth, v.videoHeight); };
                v.onerror = function () { finish(0, 0); };
                setTimeout(function () { finish(0, 0); }, 5000);
                v.src = url;
            });
        }

        // Pasang file ke <input type=file>
        function setInputFile(input, file) {
            try {
                var dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                return true;
            } catch (e) { return false; }
        }

        function qualityText(srcW, srcH, finalW, finalH, kind, changed) {
            var best = Math.max(srcW, srcH);
            var cls, msg;
            if (best >= 1920) {
                cls = 'ok';
                msg = '✅ Kualitas HD (' + srcW + '×' + srcH + ' px)';
            } else if (best >= 1280) {
                cls = 'ok';
                msg = '👍 Kualitas baik (' + srcW + '×' + srcH + ' px)';
            } else {
                cls = 'warn';
                msg = '⚠️ Resolusi ' + kind + ' kecil (' + srcW + '×' + srcH + ' px). Hasil dihaluskan otomatis, tetapi untuk hasil paling tajam gunakan ' + kind + ' minimal 1920 px.';
            }
            if (changed && finalW && finalW !== srcW) {
                msg += ' → dioptimalkan ke ' + finalW + '×' + finalH + ' px';
            }
            return { cls: cls, msg: msg };
        }

        return {
            processImage: processImage,
            videoInfo: videoInfo,
            setInputFile: setInputFile,
            qualityText: qualityText
        };
    })();

    function showQuality(el, cls, msg) {
        el.hidden = false;
        el.className = 'quality ' + cls;
        el.textContent = msg;
    }

    /* ===== Editor sampul profil (termasuk geser & zoom foto/video) ===== */
    (function () {
        var COVER_VIDEO_MAX_MB = 20;
        var COVER_IMG_MAX_MB = 20;
        var RAW_IMG_MAX_MB = 60;
        var MIN_ZOOM = 1;
        var MAX_ZOOM = 3;
        var DEFAULT_BG = 'radial-gradient(circle at 12% 20%, rgba(255,255,255,.25), transparent 40%), linear-gradient(120deg, #b91c1c, #dc2626 55%, #f97316)';

        var preview = document.getElementById('coverPreview');
        var badge = document.getElementById('coverBadge');
        var fileEl = document.getElementById('cover_file');
        var fileName = document.getElementById('coverFileName');
        var qualityEl = document.getElementById('coverQuality');
        var c1 = document.getElementById('cover_color');
        var c2 = document.getElementById('cover_color2');
        var radios = document.querySelectorAll('input[name="cover_type"]');
        var panels = {
            gradient: document.getElementById('panel-gradient'),
            media: document.getElementById('panel-media')
        };

        // Kontrol posisi & zoom
        var adjustBox = document.getElementById('coverAdjust');
        var zoomEl = document.getElementById('coverZoom');
        var hidX = document.getElementById('cover_pos_x');
        var hidY = document.getElementById('cover_pos_y');
        var hidZ = document.getElementById('cover_zoom');

        var saved = @json(['type' => $coverType, 'url' => $coverUrl]);
        var picked = null;
        var objUrl = null;

        // Posisi: x,y = persen (0-100) seperti object-position, z = zoom (1-3)
        var pos = {
            x: clamp(parseFloat(hidX.value), 0, 100, 50),
            y: clamp(parseFloat(hidY.value), 0, 100, 50),
            z: clamp(parseFloat(hidZ.value), MIN_ZOOM, MAX_ZOOM, 1)
        };

        function clamp(v, min, max, fallback) {
            if (!isFinite(v)) return fallback;
            return Math.max(min, Math.min(max, v));
        }

        function choice() {
            var r = document.querySelector('input[name="cover_type"]:checked');
            return r ? r.value : 'default';
        }

        function currentMedia() {
            return preview.querySelector('img, video');
        }

        function clearMedia() {
            preview.querySelectorAll('img, video').forEach(function (el) { el.remove(); });
        }

        /* Terapkan posisi & zoom ke elemen media + kolom tersembunyi.
         * object-position + transform-origin yang sama + scale(zoom) membuat
         * tampilan identik di bingkai berukuran berapa pun (aman dipakai di
         * halaman profil). */
        function applyPos() {
            var el = currentMedia();

            if (el) {
                el.style.objectPosition = pos.x + '% ' + pos.y + '%';
                el.style.transformOrigin = pos.x + '% ' + pos.y + '%';
                el.style.transform = 'scale(' + pos.z + ')';
            }

            hidX.value = pos.x.toFixed(2);
            hidY.value = pos.y.toFixed(2);
            hidZ.value = pos.z.toFixed(2);
            zoomEl.value = pos.z;
        }

        function resetPos() {
            pos.x = 50; pos.y = 50; pos.z = 1;
            applyPos();
        }

        function addMedia(url, isVideo) {
            var el;
            if (isVideo) {
                el = document.createElement('video');
                el.src = url;
                el.muted = true; el.loop = true; el.autoplay = true; el.playsInline = true;
                el.preload = 'auto';
                el.setAttribute('muted', ''); el.setAttribute('playsinline', '');
                el.play && el.play().catch(function () {});
            } else {
                el = document.createElement('img');
                el.decoding = 'async';
                el.draggable = false;
                el.src = url;
                el.alt = 'Pratinjau sampul';
            }
            // Sisipkan di belakang hint & badge
            preview.insertBefore(el, preview.firstChild);
        }

        function refresh() {
            var type = choice();

            Object.keys(panels).forEach(function (k) {
                panels[k].classList.toggle('show', k === type);
            });

            clearMedia();

            var hasMedia = false;

            if (type === 'default') {
                preview.style.background = DEFAULT_BG;
                badge.textContent = 'Sampul default';
            } else if (type === 'gradient') {
                preview.style.background = 'linear-gradient(120deg,' + c1.value + ',' + c2.value + ')';
                badge.textContent = 'Warna / gradien';
            } else {
                preview.style.background = '#111827';
                if (picked) {
                    addMedia(picked.url, picked.isVideo);
                    badge.textContent = picked.isVideo ? 'Video (baru)' : 'Foto (baru)';
                    hasMedia = true;
                } else if (saved.url && (saved.type === 'image' || saved.type === 'video')) {
                    addMedia(saved.url, saved.type === 'video');
                    badge.textContent = saved.type === 'video' ? 'Video saat ini' : 'Foto saat ini';
                    hasMedia = true;
                } else {
                    preview.style.background = DEFAULT_BG;
                    badge.textContent = 'Pilih foto atau video';
                }
            }

            preview.classList.toggle('adjustable', hasMedia);
            adjustBox.classList.toggle('show', hasMedia);

            applyPos();
        }

        /* ----- Seret untuk menggeser ----- */
        var drag = null;

        // Seberapa jauh media "melebihi" bingkai (px) pada tiap sumbu
        function overflow() {
            var el = currentMedia();
            if (!el) return { x: 0, y: 0 };

            var nw = el.tagName === 'VIDEO' ? el.videoWidth : el.naturalWidth;
            var nh = el.tagName === 'VIDEO' ? el.videoHeight : el.naturalHeight;
            var fw = preview.clientWidth;
            var fh = preview.clientHeight;

            if (!nw || !nh || !fw || !fh) return { x: 0, y: 0 };

            var cover = Math.max(fw / nw, fh / nh);   // skala object-fit: cover

            return {
                x: nw * cover * pos.z - fw,
                y: nh * cover * pos.z - fh
            };
        }

        preview.addEventListener('pointerdown', function (e) {
            if (!preview.classList.contains('adjustable')) return;
            if (e.button !== undefined && e.button !== 0) return;

            drag = { id: e.pointerId, x: e.clientX, y: e.clientY };
            preview.classList.add('dragging');

            try { preview.setPointerCapture(e.pointerId); } catch (err) { /* abaikan */ }
            e.preventDefault();
        });

        preview.addEventListener('pointermove', function (e) {
            if (!drag || drag.id !== e.pointerId) return;

            var dx = e.clientX - drag.x;
            var dy = e.clientY - drag.y;

            drag.x = e.clientX;
            drag.y = e.clientY;

            var ov = overflow();

            // Media mengikuti arah jari/kursor: geser ke kanan => bagian kiri foto terlihat
            if (ov.x > 1) pos.x = clamp(pos.x - (dx / ov.x) * 100, 0, 100, pos.x);
            if (ov.y > 1) pos.y = clamp(pos.y - (dy / ov.y) * 100, 0, 100, pos.y);

            applyPos();
        });

        function endDrag(e) {
            if (!drag || (e && drag.id !== e.pointerId)) return;
            drag = null;
            preview.classList.remove('dragging');
        }
        preview.addEventListener('pointerup', endDrag);
        preview.addEventListener('pointercancel', endDrag);

        /* ----- Zoom: scroll mouse & slider ----- */
        preview.addEventListener('wheel', function (e) {
            if (!preview.classList.contains('adjustable')) return;
            e.preventDefault();
            pos.z = clamp(pos.z * (e.deltaY < 0 ? 1.06 : 0.94), MIN_ZOOM, MAX_ZOOM, pos.z);
            applyPos();
        }, { passive: false });

        zoomEl.addEventListener('input', function () {
            pos.z = clamp(parseFloat(zoomEl.value), MIN_ZOOM, MAX_ZOOM, 1);
            applyPos();
        });

        document.getElementById('coverCenter').addEventListener('click', function () {
            pos.x = 50; pos.y = 50;
            applyPos();
        });

        document.getElementById('coverReset').addEventListener('click', resetPos);

        /* ----- Pilihan jenis & warna sampul ----- */
        radios.forEach(function (r) { r.addEventListener('change', refresh); });
        c1.addEventListener('input', refresh);
        c2.addEventListener('input', refresh);

        document.querySelectorAll('#presets .preset').forEach(function (b) {
            b.addEventListener('click', function () {
                c1.value = b.dataset.c1;
                c2.value = b.dataset.c2;
                refresh();
            });
        });

        /* ----- Pilih file foto / video ----- */
        fileEl.addEventListener('change', async function () {
            var f = fileEl.files && fileEl.files[0];
            if (!f) return;

            var isImg = /^image\/(jpeg|png|webp)$/.test(f.type);
            var isVid = /^video\/(mp4|webm)$/.test(f.type);

            if (!isImg && !isVid) {
                alert('Format harus JPG, PNG, WEBP, MP4, atau WEBM.');
                fileEl.value = '';
                return;
            }

            var finalFile = f;
            var info = null;

            if (isVid) {
                if (f.size > COVER_VIDEO_MAX_MB * 1024 * 1024) {
                    alert('Ukuran video maksimal ' + COVER_VIDEO_MAX_MB + ' MB.');
                    fileEl.value = '';
                    return;
                }
                showQuality(qualityEl, 'busy', 'Memeriksa video...');
                var vi = await HD.videoInfo(f);
                if (vi.w) {
                    var q = HD.qualityText(vi.w, vi.h, 0, 0, 'video', false);
                    showQuality(qualityEl, q.cls, q.msg);
                } else {
                    qualityEl.hidden = true;
                }
            } else {
                if (f.size > RAW_IMG_MAX_MB * 1024 * 1024) {
                    alert('Ukuran foto terlalu besar (maksimal ' + RAW_IMG_MAX_MB + ' MB).');
                    fileEl.value = '';
                    return;
                }
                showQuality(qualityEl, 'busy', '⏳ Mengoptimalkan foto agar tetap tajam...');
                try {
                    info = await HD.processImage(f, COVER_IMG_MAX_MB);
                    finalFile = info.file;
                    if (info.changed && !HD.setInputFile(fileEl, finalFile)) {
                        finalFile = f; // browser lama: kirim file asli
                    }
                    var qi = HD.qualityText(info.srcW, info.srcH, info.w, info.h, 'foto', info.changed && finalFile !== f);
                    showQuality(qualityEl, qi.cls, qi.msg);
                } catch (err) {
                    finalFile = f;
                    if (f.size > COVER_IMG_MAX_MB * 1024 * 1024) {
                        alert('Foto tidak bisa diproses dan ukurannya melebihi ' + COVER_IMG_MAX_MB + ' MB.');
                        fileEl.value = '';
                        qualityEl.hidden = true;
                        return;
                    }
                    qualityEl.hidden = true;
                }
            }

            if (objUrl) URL.revokeObjectURL(objUrl);
            objUrl = URL.createObjectURL(finalFile);
            picked = { url: objUrl, isVideo: isVid };
            fileName.textContent = finalFile.name;

            // File baru => posisi kembali ke tengah & zoom normal
            pos.x = 50; pos.y = 50; pos.z = 1;

            document.getElementById('ct_media').checked = true;
            refresh();
        });

        refresh();
    })();

    /* ===== Warna tulisan nama & username ===== */
    (function () {
        var DEF_NAME = '#1f2937', DEF_USER = '#dc2626';

        var pvName = document.getElementById('pvName');
        var pvUser = document.getElementById('pvUser');
        var nameInput = document.getElementById('name');
        var userInput = document.getElementById('username');

        var nameHidden = document.getElementById('name_color');
        var userHidden = document.getElementById('username_color');
        var namePick = document.getElementById('nameColorPick');
        var userPick = document.getElementById('userColorPick');

        function paint() {
            pvName.style.color = nameHidden.value || DEF_NAME;
            pvUser.style.color = userHidden.value || DEF_USER;
            pvName.textContent = nameInput.value || 'Nama penjelajah';
            pvUser.textContent = '@ ' + (userInput.value || 'username');
        }

        namePick.addEventListener('input', function () { nameHidden.value = namePick.value; paint(); });
        userPick.addEventListener('input', function () { userHidden.value = userPick.value; paint(); });

        document.getElementById('nameColorReset').addEventListener('click', function () {
            nameHidden.value = ''; namePick.value = DEF_NAME; paint();
        });
        document.getElementById('userColorReset').addEventListener('click', function () {
            userHidden.value = ''; userPick.value = DEF_USER; paint();
        });

        nameInput.addEventListener('input', paint);
        userInput.addEventListener('input', paint);

        paint();
    })();

    /* ===== Border foto profil (pratinjau langsung) ===== */
    (function () {
        var preview = document.getElementById('borderPreview');
        var nameEl = document.getElementById('borderPreviewName');
        var radios = document.querySelectorAll('input[name="avatar_border"]');

        function apply() {
            var checked = document.querySelector('input[name="avatar_border"]:checked');
            if (!checked) return;

            preview.className = 'avatar-wrap lg bd-' + checked.value;
            nameEl.textContent = checked.getAttribute('data-label') || '';
        }

        radios.forEach(function (r) { r.addEventListener('change', apply); });

        // Kalau pengguna memilih foto profil baru, pratinjau border ikut berganti
        var avatarInput = document.getElementById('avatar');
        var previewImg = document.getElementById('borderPreviewImg');
        var blobUrl = null;

        avatarInput.addEventListener('change', function () {
            var f = avatarInput.files && avatarInput.files[0];
            if (!f || !/^image\//.test(f.type)) return;

            if (blobUrl) URL.revokeObjectURL(blobUrl);
            blobUrl = URL.createObjectURL(f);

            var holder = document.getElementById('borderPreviewImg');
            if (holder.tagName === 'IMG') {
                holder.src = blobUrl;
            } else {
                var im = document.createElement('img');
                im.className = 'bd-inner';
                im.id = 'borderPreviewImg';
                im.alt = 'Pratinjau border';
                im.src = blobUrl;
                holder.replaceWith(im);
            }
        });

        apply();
    })();

    /* ===== Background halaman profil ===== */
    (function () {
        var BG_MAX_MB = 5;
        var RAW_MAX_MB = 60;

        var preview = document.getElementById('bgPreview');
        var badge = document.getElementById('bgBadge');
        var fileEl = document.getElementById('bg_file');
        var fileName = document.getElementById('bgFileName');
        var qualityEl = document.getElementById('bgQuality');

        var hidden1 = document.getElementById('bg_color');
        var hidden2 = document.getElementById('bg_color2');
        var solid = document.getElementById('bg_color_a');
        var g1 = document.getElementById('bg_color_b');
        var g2 = document.getElementById('bg_color2_b');

        var radios = document.querySelectorAll('input[name="bg_type"]');
        var panels = {
            color: document.getElementById('bgpanel-color'),
            gradient: document.getElementById('bgpanel-gradient'),
            image: document.getElementById('bgpanel-image'),
            salju: document.getElementById('bgpanel-salju')
        };

        var saved = @json(['url' => $bgUrl]);
        var picked = null;
        var objUrl = null;

        // Latar & partikel salju
        var SNOW_URL = @json($bgSnowUrl);
        var snow = SnowFX(document.getElementById('bgSnow'), { density: 5000, min: 25, max: 70, scale: 0.8 });

        function choice() {
            var r = document.querySelector('input[name="bg_type"]:checked');
            return r ? r.value : 'default';
        }

        function refresh() {
            var type = choice();

            Object.keys(panels).forEach(function (k) {
                panels[k].classList.toggle('show', k === type);
            });

            // nilai yang dikirim ke server
            if (type === 'color') {
                hidden1.value = solid.value;
            } else if (type === 'gradient') {
                hidden1.value = g1.value;
                hidden2.value = g2.value;
            }

            preview.style.backgroundImage = '';
            preview.style.backgroundSize = '';
            preview.style.backgroundPosition = '';

            // Hentikan salju kecuali bila jenis "salju" yang dipilih
            if (type !== 'salju') snow.stop();
            preview.classList.toggle('is-salju', type === 'salju');

            if (type === 'default') {
                preview.style.background = '#f6f7fb';
                badge.textContent = 'Background default';
            } else if (type === 'color') {
                preview.style.background = solid.value;
                badge.textContent = 'Warna polos';
            } else if (type === 'gradient') {
                preview.style.background = 'linear-gradient(135deg,' + g1.value + ',' + g2.value + ')';
                badge.textContent = 'Gradien';
            } else if (type === 'salju') {
                preview.style.background = 'linear-gradient(180deg, #8fbcf0 0%, #a9cdf5 50%, #c3dcf8 100%)';
                badge.textContent = 'Salju ❄️ (hiasan pinggir)';
                snow.start();
            } else {
                var url = picked ? picked.url : saved.url;
                if (url) {
                    preview.style.background = "#111827 url('" + url + "') center/cover no-repeat";
                    badge.textContent = picked ? 'Foto (baru)' : 'Foto saat ini';
                } else {
                    preview.style.background = '#f6f7fb';
                    badge.textContent = 'Pilih foto background';
                }
            }
        }

        radios.forEach(function (r) { r.addEventListener('change', refresh); });
        solid.addEventListener('input', refresh);
        g1.addEventListener('input', refresh);
        g2.addEventListener('input', refresh);

        document.querySelectorAll('#bgPresetsColor .preset').forEach(function (b) {
            b.addEventListener('click', function () { solid.value = b.dataset.c1; refresh(); });
        });
        document.querySelectorAll('#bgPresetsGrad .preset').forEach(function (b) {
            b.addEventListener('click', function () {
                g1.value = b.dataset.c1; g2.value = b.dataset.c2; refresh();
            });
        });

        fileEl.addEventListener('change', async function () {
            var f = fileEl.files && fileEl.files[0];
            if (!f) return;

            if (!/^image\/(jpeg|png|webp)$/.test(f.type)) {
                alert('Format harus JPG, PNG, atau WEBP.');
                fileEl.value = '';
                return;
            }
            if (f.size > RAW_MAX_MB * 1024 * 1024) {
                alert('Ukuran foto terlalu besar (maksimal ' + RAW_MAX_MB + ' MB).');
                fileEl.value = '';
                return;
            }

            var finalFile = f;
            showQuality(qualityEl, 'busy', '⏳ Mengoptimalkan foto agar tetap tajam...');

            try {
                var info = await HD.processImage(f, BG_MAX_MB);
                finalFile = info.file;
                if (info.changed && !HD.setInputFile(fileEl, finalFile)) {
                    finalFile = f;
                }
                var q = HD.qualityText(info.srcW, info.srcH, info.w, info.h, 'foto', info.changed && finalFile !== f);
                showQuality(qualityEl, q.cls, q.msg);
            } catch (err) {
                finalFile = f;
                if (f.size > BG_MAX_MB * 1024 * 1024) {
                    alert('Foto tidak bisa diproses dan ukurannya melebihi ' + BG_MAX_MB + ' MB.');
                    fileEl.value = '';
                    qualityEl.hidden = true;
                    return;
                }
                qualityEl.hidden = true;
            }

            if (objUrl) URL.revokeObjectURL(objUrl);
            objUrl = URL.createObjectURL(finalFile);
            picked = { url: objUrl };
            fileName.textContent = finalFile.name;

            document.getElementById('bg_image_r').checked = true;
            refresh();
        });

        refresh();
    })();

    /* ===== EDITOR FOTO PROFIL (geser, zoom, putar bebas) ===== */
    (function () {
        var V = 300;      // ukuran area kerja (px logis)
        var D = 240;      // diameter lingkaran hasil (80% dari area kerja)
        var OUT = 512;    // ukuran gambar akhir yang diunggah
        var MAX_MB = 2;
        var MAX_ZOOM = 4;

        var form = document.getElementById('profileForm');
        var input = document.getElementById('avatar');
        var stage = document.getElementById('stage');
        var canvas = document.getElementById('cropCanvas');
        var current = document.getElementById('currentAvatar');
        var controls = document.getElementById('cropControls');
        var zoomEl = document.getElementById('zoom');
        var title = document.getElementById('avatarTitle');
        var sub = document.getElementById('avatarSub');
        var saveBtn = document.getElementById('saveBtn');
        var ctx = canvas.getContext('2d');

        var dpr = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = V * dpr;
        canvas.height = V * dpr;

        var img = null;
        var base = 1, zoom = 1, minZoom = 0.5, fitZoom = 1;
        var ox = 0, oy = 0, rot = 0;
        var defaultSub = sub.textContent;

        function iw() { return (rot % 180 === 0) ? img.naturalWidth : img.naturalHeight; }
        function ih() { return (rot % 180 === 0) ? img.naturalHeight : img.naturalWidth; }
        function scale() { return base * zoom; }

        // Hitung skala dasar sesuai arah putaran saat ini
        function computeBase() {
            base = Math.max(D / iw(), D / ih());                 // foto memenuhi lingkaran
            fitZoom = Math.min(D / iw(), D / ih()) / base;        // seluruh foto terlihat
            minZoom = Math.max(0.1, fitZoom * 0.5);
            zoomEl.min = minZoom;
            zoomEl.max = MAX_ZOOM;
        }

        // Foto boleh digeser bebas, asal masih ada bagian yang menyentuh lingkaran
        function clamp() {
            if (!img) return;
            var s = scale();
            var lx = (iw() * s + D) / 2 - 40;
            var ly = (ih() * s + D) / 2 - 40;
            ox = Math.max(-lx, Math.min(lx, ox));
            oy = Math.max(-ly, Math.min(ly, oy));
        }

        // size = lebar kanvas, f = faktor skala dari unit lingkaran (D) ke piksel kanvas
        function draw(c, size, f, bg) {
            if (!img) return;
            c.setTransform(1, 0, 0, 1, 0, 0);
            c.clearRect(0, 0, size, size);
            c.fillStyle = bg;
            c.fillRect(0, 0, size, size);

            c.imageSmoothingEnabled = true;
            c.imageSmoothingQuality = 'high';

            c.save();
            c.translate(size / 2 + ox * f, size / 2 + oy * f);
            c.rotate(rot * Math.PI / 180);
            c.scale(scale() * f, scale() * f);
            c.drawImage(img, -img.naturalWidth / 2, -img.naturalHeight / 2);
            c.restore();
        }

        function render() {
            if (!img) return;
            clamp();
            draw(ctx, canvas.width, canvas.width / V, '#e5e7eb');
        }

        function setZoom(z) {
            if (!img) return;
            zoom = Math.max(minZoom, Math.min(MAX_ZOOM, Number(z) || 1));
            zoomEl.value = zoom;
            render();
        }

        function resetView() {
            computeBase();
            zoom = 1; ox = 0; oy = 0;
            zoomEl.value = zoom;
            render();
        }

        function startCrop(image, fileName) {
            img = image;
            rot = 0;

            current.hidden = true;
            canvas.hidden = false;
            stage.classList.add('editing');
            controls.classList.add('show');

            title.textContent = 'Atur posisi foto';
            sub.textContent = fileName;

            resetView();
            canvas.focus({ preventScroll: true });
        }

        function stopCrop() {
            img = null;
            input.value = '';
            canvas.hidden = true;
            current.hidden = false;
            stage.classList.remove('editing');
            controls.classList.remove('show');
            title.textContent = 'Foto saat ini';
            sub.textContent = defaultSub;
        }

        function loadFile(file) {
            if (!file) return;

            if (!/^image\/(jpeg|png|webp)$/.test(file.type)) {
                alert('Format foto harus JPG, PNG, atau WEBP.');
                stopCrop();
                return;
            }
            if (file.size > MAX_MB * 1024 * 1024) {
                alert('Ukuran foto maksimal ' + MAX_MB + ' MB.');
                stopCrop();
                return;
            }

            var url = URL.createObjectURL(file);
            var image = new Image();
            image.onload = function () {
                URL.revokeObjectURL(url);
                startCrop(image, file.name);
            };
            image.onerror = function () {
                URL.revokeObjectURL(url);
                alert('Foto tidak bisa dibuka. Coba foto lain.');
                stopCrop();
            };
            image.src = url;
        }

        input.addEventListener('change', function () {
            loadFile(input.files && input.files[0]);
        });

        /* Drag & drop foto ke area kerja */
        ['dragenter', 'dragover'].forEach(function (ev) {
            stage.addEventListener(ev, function (e) { e.preventDefault(); });
        });
        stage.addEventListener('drop', function (e) {
            e.preventDefault();
            var f = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
            if (!f) return;
            try {
                var dt = new DataTransfer();
                dt.items.add(f);
                input.files = dt.files;
            } catch (err) { /* abaikan */ }
            loadFile(f);
        });

        /* Geser dengan mouse / sentuh + cubit untuk zoom */
        var pointers = {};
        var pinchStart = null;

        function cssSize() { return canvas.getBoundingClientRect().width || V; }
        function count() { return Object.keys(pointers).length; }
        function pinchDist() {
            var ids = Object.keys(pointers);
            var a = pointers[ids[0]], b = pointers[ids[1]];
            return Math.hypot(a.x - b.x, a.y - b.y);
        }

        canvas.addEventListener('pointerdown', function (e) {
            if (!img) return;
            pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
            canvas.setPointerCapture(e.pointerId);
            canvas.classList.add('dragging');

            if (count() === 2) {
                pinchStart = { dist: pinchDist(), zoom: zoom };
            }
        });

        canvas.addEventListener('pointermove', function (e) {
            if (!img || !pointers[e.pointerId]) return;

            var prev = pointers[e.pointerId];
            var k = V / cssSize();

            if (count() === 1) {
                ox += (e.clientX - prev.x) * k;
                oy += (e.clientY - prev.y) * k;
                pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
                render();
            } else if (count() === 2 && pinchStart) {
                pointers[e.pointerId] = { x: e.clientX, y: e.clientY };
                var d = pinchDist();
                if (pinchStart.dist > 0) {
                    setZoom(pinchStart.zoom * (d / pinchStart.dist));
                }
            }
        });

        function endPointer(e) {
            delete pointers[e.pointerId];
            if (count() < 2) pinchStart = null;
            if (count() === 0) canvas.classList.remove('dragging');
        }
        canvas.addEventListener('pointerup', endPointer);
        canvas.addEventListener('pointercancel', endPointer);

        /* Zoom: slider, scroll mouse */
        zoomEl.addEventListener('input', function () { setZoom(parseFloat(zoomEl.value)); });

        canvas.addEventListener('wheel', function (e) {
            if (!img) return;
            e.preventDefault();
            setZoom(zoom * (e.deltaY < 0 ? 1.08 : 0.92));
        }, { passive: false });

        /* Panah keyboard */
        canvas.addEventListener('keydown', function (e) {
            if (!img) return;
            var step = e.shiftKey ? 20 : 6;
            if (e.key === 'ArrowLeft') ox -= step;
            else if (e.key === 'ArrowRight') ox += step;
            else if (e.key === 'ArrowUp') oy -= step;
            else if (e.key === 'ArrowDown') oy += step;
            else if (e.key === '+' || e.key === '=') { setZoom(zoom * 1.08); e.preventDefault(); return; }
            else if (e.key === '-') { setZoom(zoom * 0.92); e.preventDefault(); return; }
            else return;
            e.preventDefault();
            render();
        });

        /* Tombol-tombol */
        document.getElementById('resetCrop').addEventListener('click', function () {
            rot = 0;
            resetView();
        });

        // Seluruh foto terlihat utuh di dalam lingkaran
        document.getElementById('fitCrop').addEventListener('click', function () {
            if (!img) return;
            ox = 0; oy = 0;
            setZoom(fitZoom);
        });

        // Foto memenuhi lingkaran tanpa ruang kosong
        document.getElementById('fillCrop').addEventListener('click', function () {
            if (!img) return;
            ox = 0; oy = 0;
            setZoom(1);
        });

        function rotate(deg) {
            if (!img) return;
            rot = ((rot + deg) % 360 + 360) % 360;
            resetView();
        }
        document.getElementById('rotLeft').addEventListener('click', function () { rotate(-90); });
        document.getElementById('rotRight').addEventListener('click', function () { rotate(90); });

        document.getElementById('cancelCrop').addEventListener('click', stopCrop);

        /* Saat simpan: kirim hasil potongan (persegi 512px, tampil bulat di profil) */
        form.addEventListener('submit', function (e) {
            if (!img) return;
            e.preventDefault();
            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';

            var out = document.createElement('canvas');
            out.width = OUT; out.height = OUT;
            draw(out.getContext('2d'), OUT, OUT / D, '#ffffff');

            out.toBlob(function (blob) {
                if (!blob) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = '💾 Simpan Perubahan';
                    alert('Gagal memproses foto. Coba lagi.');
                    return;
                }
                try {
                    var file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                } catch (err) { /* browser lama: kirim foto asli */ }
                form.submit();
            }, 'image/jpeg', 0.92);
        });
    })();
})();
</script>
</body>
</html>