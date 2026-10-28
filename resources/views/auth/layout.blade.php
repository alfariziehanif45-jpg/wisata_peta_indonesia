<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · Panggung Sulap</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            min-height: 100vh;
            font-family: 'Trebuchet MS', 'Segoe UI', system-ui, sans-serif;
            display: grid; place-items: center;
            padding: 72px 16px 96px;
            overflow-x: hidden;
        }
        .serif { font-family: Georgia, 'Times New Roman', serif; }

        .wrap { position: relative; z-index: 2; width: 100%; max-width: 420px; }
        .card h1 { margin: 0 0 4px; font: 700 1.7rem/1.15 Georgia, 'Times New Roman', serif; }
        .kicker { margin: 0 0 6px; font-size: .72rem; letter-spacing: .28em; text-transform: uppercase; font-weight: 700; }
        .sub { margin: 0 0 18px; font-size: .9rem; opacity: .8; }

        form label { display: block; margin: 14px 0 6px; font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        form input[type="text"], form input[type="email"], form input[type="password"] {
            width: 100%; padding: 12px 14px; font: inherit; font-size: 1rem; outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .submit {
            width: 100%; margin-top: 22px; padding: 14px 16px; border: 0; cursor: pointer;
            font: 700 1rem/1 Georgia, 'Times New Roman', serif; letter-spacing: .08em; text-transform: uppercase;
            transition: transform .15s, box-shadow .2s, filter .2s;
        }
        .submit:hover:not(:disabled) { transform: translateY(-2px); filter: brightness(1.06); }
        .submit:active:not(:disabled) { transform: translateY(0); }
        .submit:disabled { opacity: .65; cursor: wait; }
        .bottom { margin: 20px 0 0; text-align: center; font-size: .9rem; }
        .bottom a { font-weight: 700; text-decoration: none; border-bottom: 1px dashed currentColor; }

        .alert {
            margin: 0 0 8px; padding: 10px 14px; border-radius: 8px; font-size: .88rem;
            background: #c0243c1f; border: 1px solid #c0243c88; color: #8f1528;
        }
        .alert ul { margin: 0; padding-left: 18px; }

        /* Lampu bohlam (dipakai bersama) */
        .bulbs {
            position: absolute; left: 0; right: 0; height: 26px; z-index: 1;
            background: radial-gradient(circle at 50% 50%, #fff6c9 0 3px, #f2c14e 4px 6px, #f2c14e00 9px) 0 0 / 34px 26px repeat-x;
            animation: bulbs 1.6s steps(2, jump-none) infinite;
        }
        @keyframes bulbs { 0% { filter: brightness(1); } 50% { filter: brightness(.55); } 100% { filter: brightness(1); } }

        /* =====================================================
           TEMA REGISTER : LOKET TEATER (tiket)
           ===================================================== */
        body.theme-register {
            color: #2b1a10;
            background:
                radial-gradient(ellipse at 50% 30%, #b3203a66 0%, transparent 65%),
                repeating-linear-gradient(90deg, #5c0b1a 0 40px, #6e1022 40px 80px),
                #4a0914;
        }
        .theme-register .bulbs-top { top: 22px; }
        .theme-register .bulbs-bottom { bottom: 22px; }
        .theme-register::before {
            content: ''; position: fixed; left: 0; right: 0; top: 0; height: 56px; z-index: 1;
            background: linear-gradient(180deg, #8f1528, #5c0b1a); border-bottom: 5px solid #f2c14e;
            box-shadow: 0 8px 22px #0009;
        }
        .theme-register .wrap { filter: drop-shadow(0 18px 30px #000a); }
        .theme-register .card {
            position: relative; padding: 26px 28px 28px; background: #f6e9c8;
            background-image: linear-gradient(180deg, #fff3d4 0%, #f1dfb2 100%);
            -webkit-mask:
                radial-gradient(circle 13px at 0 56px, #0000 97%, #000) left / 51% 100% no-repeat,
                radial-gradient(circle 13px at 100% 56px, #0000 97%, #000) right / 51% 100% no-repeat;
            mask:
                radial-gradient(circle 13px at 0 56px, #0000 97%, #000) left / 51% 100% no-repeat,
                radial-gradient(circle 13px at 100% 56px, #0000 97%, #000) right / 51% 100% no-repeat;
        }
        .theme-register .card::before {
            content: ''; position: absolute; inset: 8px; border: 2px solid #b3203a55; pointer-events: none;
        }
        .theme-register .ticket-head {
            display: flex; justify-content: space-between; align-items: center;
            margin: -2px 0 14px; padding-bottom: 12px; border-bottom: 2px dashed #8f152866;
        }
        .theme-register .stub { font: 700 .72rem/1 Georgia, serif; letter-spacing: .22em; color: #8f1528; }
        .theme-register .stub-no { font: 700 .72rem/1 'Courier New', monospace; color: #8f1528; }
        .theme-register .kicker { color: #8f1528; }
        .theme-register .card h1 { color: #2b1a10; }
        .theme-register form label { color: #5a3a24; }
        .theme-register form input[type="text"],
        .theme-register form input[type="email"],
        .theme-register form input[type="password"] {
            background: #fffaf0; color: #2b1a10; border: 1px solid #c9ad78; border-bottom: 2px solid #8f1528; border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        .theme-register form input:focus { border-color: #c0243c; box-shadow: 0 0 0 3px #c0243c33; background: #fff; }
        .theme-register .submit {
            color: #fff3d4; border-radius: 4px;
            background: linear-gradient(180deg, #c0243c, #8f1528);
            box-shadow: 0 4px 0 #5c0b1a, 0 8px 16px #0005;
        }
        .theme-register .bottom { color: #5a3a24; }
        .theme-register .bottom a { color: #8f1528; }
        .theme-register .barcode {
            margin-top: 20px; height: 34px; opacity: .75;
            background: repeating-linear-gradient(90deg, #2b1a10 0 2px, transparent 2px 5px, #2b1a10 5px 6px, transparent 6px 9px, #2b1a10 9px 12px, transparent 12px 14px);
            -webkit-mask: linear-gradient(90deg, #000 0 100%); mask: linear-gradient(90deg, #000 0 100%);
        }

        /* =====================================================
           TEMA LOGIN : PANGGUNG PERTUNJUKAN (kartu remi + sorot lampu)
           ===================================================== */
        body.theme-login {
            color: #f3ecff;
            background:
                radial-gradient(ellipse at 50% 0%, #4a2f8a99 0%, transparent 60%),
                linear-gradient(180deg, #120d2b 0%, #24163f 70%, #120d2b 100%);
        }
        .theme-login .spot {
            position: fixed; z-index: 0; top: -40px; left: 50%; width: min(760px, 120vw); height: 85vh;
            transform: translateX(-50%); pointer-events: none;
            background: linear-gradient(180deg, #fff3c455 0%, #fff3c414 60%, transparent 100%);
            clip-path: polygon(44% 0, 56% 0, 100% 100%, 0 100%);
            animation: sway 7s ease-in-out infinite alternate;
        }
        @keyframes sway { from { transform: translateX(-50%) rotate(-1.6deg); } to { transform: translateX(-50%) rotate(1.6deg); } }
        .theme-login .floor {
            position: fixed; z-index: 0; left: 0; right: 0; bottom: 0; height: 74px;
            background:
                repeating-linear-gradient(90deg, #00000033 0 2px, transparent 2px 70px),
                linear-gradient(180deg, #6b3f1d, #3b200c);
            border-top: 5px solid #f2c14e;
            box-shadow: 0 -14px 40px #f2c14e22;
        }
        .theme-login .bulbs-bottom { bottom: 84px; z-index: 1; }
        .theme-login .bulbs-top { top: 14px; }
        .theme-login .card {
            position: relative; padding: 30px 28px 28px; border-radius: 18px;
            background: linear-gradient(160deg, #2e1d57ee, #1a1038ee);
            border: 2px solid #f2c14e;
            box-shadow: 0 0 0 6px #120d2b, 0 0 0 8px #f2c14e66, 0 24px 60px #000b, 0 0 60px #7a4be033;
            backdrop-filter: blur(4px);
        }
        .theme-login .corner {
            position: absolute; font: 700 1.1rem/1 Georgia, serif; text-align: center; color: #f2c14e;
        }
        .theme-login .corner small { display: block; font-size: .95rem; margin-top: 2px; }
        .theme-login .corner.tl { top: 12px; left: 16px; }
        .theme-login .corner.br { bottom: 12px; right: 16px; transform: rotate(180deg); }
        .theme-login .kicker { color: #f2c14e; text-align: center; }
        .theme-login .card h1 { text-align: center; color: #fff; text-shadow: 0 2px 14px #7a4be0aa; }
        .theme-login .sub { text-align: center; color: #d9ccff; }
        .theme-login .alert { background: #c0243c33; border-color: #ff7a8f88; color: #ffd6dc; }
        .theme-login form label { color: #f2c14e; }
        .theme-login form input[type="text"],
        .theme-login form input[type="email"],
        .theme-login form input[type="password"] {
            background: #ffffff10; color: #fff; border: 1px solid #f2c14e66; border-radius: 10px;
        }
        .theme-login form input::placeholder { color: #ffffff66; }
        .theme-login form input:focus { border-color: #f2c14e; background: #ffffff1c; box-shadow: 0 0 0 3px #f2c14e33, 0 0 18px #f2c14e33; }
        .theme-login .submit {
            color: #2a1a05; border-radius: 10px;
            background: linear-gradient(180deg, #ffd978, #f2c14e 55%, #d9a22e);
            box-shadow: 0 4px 0 #8a6414, 0 10px 22px #0007, 0 0 24px #f2c14e44;
        }
        .theme-login .bottom { color: #d9ccff; }
        .theme-login .bottom a { color: #f2c14e; }

        @media (max-width: 420px) {
            .theme-register .card, .theme-login .card { padding-left: 20px; padding-right: 20px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .bulbs, .theme-login .spot { animation: none; }
        }
    </style>
</head>
<body class="@yield('theme', 'theme-login')">

    <div class="bulbs bulbs-top"></div>
    <div class="bulbs bulbs-bottom"></div>

    @hasSection('theme')
        @if (trim($__env->yieldContent('theme')) === 'theme-login')
            <div class="spot"></div>
            <div class="floor"></div>
        @endif
    @endif

    <main class="wrap">
        <div class="card">
            @yield('card_top')

            <p class="kicker">@yield('kicker')</p>
            <h1>@yield('heading')</h1>
            @hasSection('subtitle')
                <p class="sub">@yield('subtitle')</p>
            @endif

            @if ($errors->any())
                <div class="alert" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

            @yield('card_bottom')
        </div>
    </main>

</body>
</html>