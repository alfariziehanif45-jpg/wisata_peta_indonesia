<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Jelajah Nusantara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #08100e;
            --panel: #11241e;
            --panel-2: #0d1b17;
            --gold: #d4af37;
            --gold-dark: #aa8c2c;
            --gold-deep: #6b5717;
            --leaf: #2d4a3e;
            --cream: #e0d8c3;
            --muted: #8c9e98;
            --danger: #ff7a8f;
        }
        * { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }
        body {
            margin: 0; min-height: 100vh; min-height: 100dvh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Poppins', sans-serif; color: #fff;
            background:
                radial-gradient(900px 500px at 15% 0%, rgba(212,175,55,.10), transparent 60%),
                radial-gradient(800px 500px at 100% 100%, rgba(61,125,90,.18), transparent 60%),
                var(--bg);
            overflow-x: hidden;
        }
        body.is-dark { overflow: hidden; }

        /* ============ Kartu utama ============ */
        .stage-container {
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1.15fr .85fr;
            width: calc(100% - 40px); max-width: 1180px;
            grid-template-rows: minmax(0, 1fr);
            height: calc(100vh - 40px); height: calc(100dvh - 40px); min-height: 560px; max-height: 820px;
            margin: 20px;
            background: var(--panel);
            border-radius: 28px; overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,.75), inset 0 0 0 3px var(--gold);
        }
        .stage-left {
            position: relative; overflow: hidden; min-height: 100%;
            border-right: 3px solid var(--gold);
            background: #0a1713;
        }
        .stage-right {
            position: relative; z-index: 2;
            display: flex; flex-direction: column; justify-content: center; justify-content: safe center;
            overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--leaf) transparent;
            padding: 48px clamp(28px, 5vw, 64px);
            background-color: var(--panel);
            /* motif batik kawung sederhana */
            background-image:
                radial-gradient(circle at 20px 20px, rgba(212,175,55,.07) 6px, transparent 7px),
                radial-gradient(circle at 0 0, rgba(212,175,55,.05) 6px, transparent 7px),
                radial-gradient(circle at 40px 40px, rgba(212,175,55,.05) 6px, transparent 7px);
            background-size: 40px 40px;
        }
        .stage-right h1 {
            margin: 0 0 8px; font-family: 'Playfair Display', serif; font-weight: 700;
            font-size: clamp(1.8rem, 3vw, 2.3rem); line-height: 1.15; color: var(--gold);
            text-shadow: 0 2px 6px rgba(0,0,0,.5);
        }
        .stage-right p { margin: 0 0 24px; color: var(--muted); font-size: .95rem; line-height: 1.55; }

        /* ============ Form ============ */
        label {
            display: block; margin: 0 0 6px; font-size: .82rem; font-weight: 500;
            color: var(--cream); letter-spacing: .2px;
        }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 13px 15px; margin-bottom: 16px;
            border-radius: 10px; border: 1px solid var(--leaf);
            background: rgba(8,16,14,.85); color: #fff;
            font: 400 .95rem 'Poppins', sans-serif;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }
        input::placeholder { color: #55675f; }
        input:hover { border-color: #3d6455; }
        input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(212,175,55,.22); background: #060c0a; }
        input.has-error { border-color: var(--danger) !important; box-shadow: 0 0 0 3px rgba(255,122,143,.2) !important; }
        input[type="checkbox"] { accent-color: var(--gold); }

        .submit {
            width: 100%; padding: 15px; margin-top: 6px;
            background: linear-gradient(135deg, #e3c04e 0%, var(--gold-dark) 100%);
            color: #08100e; border: none; border-radius: 10px;
            font: 700 1rem 'Poppins', sans-serif; letter-spacing: .4px;
            cursor: pointer; box-shadow: 0 4px 0 var(--gold-deep);
            transition: transform .1s, box-shadow .1s, filter .2s;
        }
        .submit:hover { filter: brightness(1.07); }
        .submit:active { transform: translateY(4px); box-shadow: 0 0 0 var(--gold-deep); }
        .submit:disabled { opacity: .7; cursor: not-allowed; transform: none; box-shadow: none; }
        .submit:focus-visible, .google-btn:focus-visible, a:focus-visible { outline: 3px solid #fff; outline-offset: 3px; }

        .bottom { text-align: center; margin: 20px 0 0 !important; font-size: .9rem !important; color: var(--muted); }
        .bottom a, .stage-right a.link { color: var(--gold); text-decoration: none; font-weight: 600; }
        .bottom a:hover { text-decoration: underline; }

        .alert {
            background: #fdecea; color: #b3261e; border: 1px solid #f5c6c2;
            border-radius: 10px; padding: 10px 12px; margin: 0 0 14px; font-size: 14px;
        }
        .google-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 11px 14px; background: #fff; color: #3c4043;
            border: 1px solid #dadce0; border-radius: 10px;
            font: 500 .92rem 'Poppins', sans-serif; text-decoration: none; transition: background .2s, transform .1s;
        }
        .google-btn:hover { background: #f1f3f4; }
        .google-btn:active { transform: scale(.99); }
        .divider { display: flex; align-items: center; gap: 12px; margin: 16px 0; color: var(--muted); font-size: .8rem; }
        .divider .line { flex: 1; height: 1px; background: var(--leaf); }

        /* ============ Layar pendek: form dirapatkan agar kartu pas satu layar ============ */
        @media (min-width: 901px) and (max-height: 840px) {
            .stage-container .stage-right { padding-top: 26px; padding-bottom: 26px; }
            .stage-container .stage-right h1 { font-size: 1.7rem; margin-bottom: 4px; }
            .stage-container .stage-right p { margin-bottom: 14px; font-size: .88rem; }
            .stage-container .stage-right input { padding: 10px 13px; margin-bottom: 10px; }
            .stage-container .stage-right label { margin-top: 0; font-size: .78rem; }
            .stage-container .stage-right .divider { margin: 10px 0; }
            .stage-container .stage-right .submit { padding: 12px; }
            .stage-container .stage-right .bottom { margin-top: 12px !important; }
        }

        /* ============ Responsif ============ */
        @media (max-width: 900px) {
            body { align-items: flex-start; }
            .stage-container {
                grid-template-columns: 1fr; grid-template-rows: 300px auto;
                width: 100%; margin: 0; height: auto; max-height: none; min-height: 100vh; min-height: 100dvh; border-radius: 0; box-shadow: none;
            }
            .stage-left { border-right: none; border-bottom: 3px solid var(--gold); min-height: 0; }
            .stage-right { padding: 32px 22px 40px; overflow: visible; }
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
</body>
</html>