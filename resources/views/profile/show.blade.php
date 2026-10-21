<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - Jelajah Wisata Indonesia</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --red: #e11d48;
            --red-dark: #be123c;
            --red-soft: #fff1f2;
            --orange: #fb923c;
            --amber: #f59e0b;
            --ink: #0f172a;
            --ink-2: #334155;
            --muted: #64748b;
            --line: rgba(15, 23, 42, .08);
            --bg: #f4f5fa;
            --card: rgba(255, 255, 255, .86);
            --card-solid: #ffffff;
            --grad: linear-gradient(135deg, #e11d48 0%, #f43f5e 45%, #fb923c 100%);
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, .04), 0 4px 14px rgba(15, 23, 42, .05);
            --shadow: 0 2px 4px rgba(15, 23, 42, .04), 0 18px 40px rgba(15, 23, 42, .08);
            --shadow-hover: 0 6px 12px rgba(15, 23, 42, .06), 0 26px 50px rgba(225, 29, 72, .16);
            --radius: 26px;
        }

        * { box-sizing: border-box; }

        html { background: var(--bg); scroll-behavior: smooth; }

        body {
            margin: 0;
            min-height: 100vh;
            background: transparent;
            color: var(--ink);
            font-family: "Plus Jakarta Sans", "Inter", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
        }

        /* ===== LAPISAN BACKGROUND (diatur dari halaman edit) ===== */
        .page-bg {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(900px 500px at 8% -5%, rgba(244, 63, 94, .16), transparent 60%),
                radial-gradient(800px 500px at 100% 0%, rgba(251, 146, 60, .16), transparent 55%),
                radial-gradient(700px 500px at 50% 110%, rgba(99, 102, 241, .10), transparent 60%),
                var(--bg);
        }

        .snow-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .snow-frame,
        .snow-side {
            position: fixed;
            pointer-events: none;
            z-index: -1;
        }

        .snow-frame {
            left: 0;
            right: 0;
            height: clamp(150px, 28vh, 260px);
            background-repeat: repeat-x;
            background-size: 520px auto;
        }

        .snow-frame.top {
            top: 0;
            background-position: top center;
            -webkit-mask-image: linear-gradient(to bottom, #000 55%, transparent);
            mask-image: linear-gradient(to bottom, #000 55%, transparent);
        }

        .snow-frame.bottom {
            bottom: 0;
            background-position: bottom center;
            -webkit-mask-image: linear-gradient(to top, #000 55%, transparent);
            mask-image: linear-gradient(to top, #000 55%, transparent);
        }

        .snow-side {
            top: 0;
            bottom: 0;
            width: clamp(40px, 8vw, 120px);
        }
        .snow-side.left  { left: 0;  background: linear-gradient(to right, rgba(255,255,255,.55), transparent); }
        .snow-side.right { right: 0; background: linear-gradient(to left,  rgba(255,255,255,.55), transparent); }

        .container {
            max-width: 1040px;
            margin: auto;
            padding: 28px 20px 70px;
        }

        /* ===== ANIMASI MASUK ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .anim { animation: fadeUp .6s cubic-bezier(.2, .7, .2, 1) both; }
        .d1 { animation-delay: .05s; }
        .d2 { animation-delay: .12s; }
        .d3 { animation-delay: .19s; }
        .d4 { animation-delay: .26s; }
        .d5 { animation-delay: .33s; }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .anim { animation: none; }
            * { transition: none !important; }
        }

        /* ===== ALERT ===== */
        .success, .errors {
            padding: 14px 18px;
            border-radius: 16px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .success { background: rgba(240, 253, 244, .92); border: 1px solid #bbf7d0; color: #166534; }
        .errors  { background: rgba(254, 242, 242, .92); border: 1px solid #fecaca; color: #991b1b; }
        .errors ul { margin: 8px 0 0; padding-left: 20px; }

        /* ===== KARTU GLASS UMUM ===== */
        .glass {
            background: var(--card);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            backdrop-filter: blur(18px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, .7);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        /* ===== PROFILE CARD ===== */
        .profile-card { overflow: hidden; }

        .cover {
            height: 210px;
            position: relative;
            overflow: hidden;
            background: #111827;
        }

        .cover.is-default {
            background:
                radial-gradient(circle at 15% 20%, rgba(255,255,255,.32), transparent 42%),
                radial-gradient(circle at 85% 85%, rgba(255,255,255,.22), transparent 46%),
                linear-gradient(120deg, #9f1239, #e11d48 45%, #fb923c);
        }

        .cover.is-default::before {
            content: "";
            position: absolute;
            width: 340px; height: 340px;
            right: -80px; top: -140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .13);
            filter: blur(2px);
        }

        .cover.is-default::after,
        .cover.is-gradient::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.2) 1.5px, transparent 1.5px);
            background-size: 22px 22px;
            opacity: .55;
            pointer-events: none;
        }

        .cover img,
        .cover video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            will-change: transform;
            backface-visibility: hidden;
        }

        /* Gradasi halus di bawah sampul agar menyatu dengan kartu */
        .cover.is-media::after {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 70px;
            background: linear-gradient(to top, rgba(0,0,0,.28), transparent);
            pointer-events: none;
        }

        .head {
            display: flex;
            gap: 24px;
            align-items: flex-end;
            flex-wrap: wrap;
            padding: 0 34px;
            margin-top: -70px;
            position: relative;
            z-index: 1;
        }

        .avatar-wrap {
            padding: 5px;
            border-radius: 50%;
            background: var(--grad);
            box-shadow: 0 14px 30px rgba(225, 29, 72, .32);
        }

        .avatar {
            display: block;
            width: 132px;
            height: 132px;
            object-fit: cover;
            border: 5px solid #fff;
            border-radius: 50%;
            background: #fff7ed;
        }

        .avatar-fallback {
            display: grid;
            place-items: center;
            font-size: 54px;
            background: linear-gradient(135deg, #fff1f2, #ffedd5);
        }

        .info {
            flex: 1;
            min-width: 230px;
            padding-bottom: 8px;
        }

        h1 {
            font-size: 31px;
            line-height: 1.15;
            margin: 0 0 4px;
            letter-spacing: -.03em;
            font-weight: 800;
            color: var(--ink);
            overflow-wrap: anywhere;
        }

        .username {
            color: var(--red);
            font-weight: 700;
            font-size: 15px;
            overflow-wrap: anywhere;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            background: rgba(255, 255, 255, .75);
            border: 1px solid var(--line);
            color: var(--ink-2);
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
        }

        .head-action {
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bio {
            position: relative;
            margin: 26px 34px 0;
            padding: 18px 22px 18px 54px;
            background: linear-gradient(135deg, rgba(255, 241, 242, .85), rgba(255, 247, 237, .85));
            border: 1px solid rgba(244, 63, 94, .14);
            border-radius: 18px;
            font-size: 15.5px;
            color: var(--ink-2);
            line-height: 1.75;
            overflow-wrap: anywhere;
        }

        .bio::before {
            content: "“";
            position: absolute;
            left: 18px;
            top: 4px;
            font-size: 54px;
            line-height: 1;
            font-weight: 800;
            color: var(--red);
            opacity: .5;
        }

        /* ===== STATS ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            padding: 26px 34px 34px;
        }

        .stat {
            display: block;
            text-align: left;
            padding: 18px;
            border-radius: 20px;
            border: 1px solid var(--line);
            background: var(--card-solid);
            color: inherit;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }

        .stat:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(244, 63, 94, .25);
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            margin-bottom: 14px;
            display: grid;
            place-items: center;
            border-radius: 15px;
            background: var(--red-soft);
            font-size: 21px;
            color: var(--red);
        }

        .stat:nth-child(1) .stat-icon { background: #fff1f2; color: #e11d48; }
        .stat:nth-child(2) .stat-icon { background: #fdf2f8; color: #db2777; }
        .stat:nth-child(3) .stat-icon { background: #fff7ed; color: #ea580c; }
        .stat:nth-child(4) .stat-icon { background: #eef2ff; color: #4f46e5; }

        .num {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.05;
        }

        .stat-label {
            font-size: 13px;
            color: var(--muted);
            margin-top: 5px;
            font-weight: 500;
        }

        /* ===== MENU ===== */
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 12px;
            margin-top: 22px;
        }

        .item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: var(--ink);
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 700;
            background: var(--card);
            -webkit-backdrop-filter: blur(14px);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, .7);
            border-radius: 18px;
            box-shadow: var(--shadow-sm);
            transition: transform .25s, box-shadow .25s;
        }

        .item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .item-icon {
            flex: none;
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: var(--grad);
            font-size: 18px;
            color: #fff;
            box-shadow: 0 6px 14px rgba(225, 29, 72, .28);
        }

        .item-arrow {
            margin-left: auto;
            color: #cbd5e1;
            font-size: 20px;
            transition: transform .2s, color .2s;
        }

        .item:hover .item-arrow {
            transform: translateX(4px);
            color: var(--red);
        }

        /* ===== ICON ===== */
        .ico { width: 1.15em; height: 1.15em; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        .btn.icon-only {
            position: relative;
            width: 46px;
            height: 46px;
            padding: 0;
            justify-content: center;
            color: var(--red);
            font-size: 20px;
            border-radius: 14px;
        }

        .btn.icon-only:hover { background: var(--red-soft); border-color: #fecdd3; }

        .notif {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 21px;
            height: 21px;
            padding: 0 5px;
            display: grid;
            place-items: center;
            border-radius: 99px;
            background: var(--grad);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            border: 2px solid #fff;
        }

        .item .notif { position: static; margin-left: auto; border: 0; }
        .item .notif + .item-arrow { margin-left: 8px; }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 12px 20px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid rgba(15, 23, 42, .1);
            color: var(--ink);
            background: #fff;
            cursor: pointer;
            font-family: inherit;
            transition: transform .18s, box-shadow .18s, background .18s;
        }

        .btn:hover {
            transform: translateY(-2px);
            background: #f8fafc;
            box-shadow: var(--shadow-sm);
        }

        .btn:focus-visible,
        .small-btn:focus-visible,
        .item:focus-visible,
        .stat:focus-visible { outline: 3px solid rgba(225, 29, 72, .55); outline-offset: 2px; }

        .btn.red {
            background: var(--grad);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 10px 22px rgba(225, 29, 72, .32);
        }

        .btn.red:hover {
            background: var(--grad);
            filter: brightness(1.06);
            box-shadow: 0 14px 28px rgba(225, 29, 72, .4);
        }

        /* ===== SECTION UMUM ===== */
        .friend-section,
        .favorite-section,
        .achievement-section {
            margin-top: 30px;
            padding: 28px 32px 32px;
            scroll-margin-top: 20px;
        }

        .favorite-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .favorite-heading h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 21px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -.02em;
        }

        .favorite-heading h2 .h-ico {
            width: 38px; height: 38px;
            display: grid; place-items: center;
            border-radius: 12px;
            background: var(--red-soft);
            font-size: 18px;
        }

        /* ===== TEMAN ===== */
        .friend-strip {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(96px, 1fr));
            gap: 16px;
        }

        .friend-chip {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            overflow-wrap: anywhere;
            padding: 14px 6px;
            border-radius: 18px;
            transition: background .2s, transform .2s;
        }

        .friend-chip:hover { background: rgba(255, 241, 242, .8); transform: translateY(-2px); }

        .friend-avatar {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            object-fit: cover;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #ffe4e6, #ffedd5);
            color: var(--red-dark);
            font-weight: 800;
            font-size: 22px;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px rgba(244, 63, 94, .35), var(--shadow-sm);
        }

        .friend-chip small { display: block; color: var(--red); font-weight: 600; font-size: 12px; }

        .friend-add { text-decoration: none; color: var(--red); }

        .friend-add .friend-avatar {
            background: var(--red-soft);
            border: 2px dashed #fda4af;
            box-shadow: none;
            font-size: 22px;
        }

        .friend-add:hover .friend-avatar { background: #ffe4e6; }

        /* ===== FAVORITES ===== */
        .favorite-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 18px;
        }

        .favorite-card {
            display: flex;
            flex-direction: column;
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 20px;
            overflow: hidden;
            overflow-wrap: anywhere;
            background: var(--card-solid);
            position: relative;
            box-shadow: var(--shadow-sm);
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }

        .favorite-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: var(--grad);
        }

        .favorite-card::after {
            content: "";
            position: absolute;
            width: 150px; height: 150px;
            right: -50px; top: -60px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(244, 63, 94, .12), transparent 70%);
            pointer-events: none;
        }

        .favorite-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(244, 63, 94, .3);
        }

        .category {
            align-self: flex-start;
            padding: 5px 12px;
            border-radius: 999px;
            background: var(--red-soft);
            color: var(--red-dark);
            font-size: 12px;
            font-weight: 800;
            margin: 8px 0 12px;
            letter-spacing: .01em;
        }

        .favorite-card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -.01em;
        }

        .favorite-card p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65;
            margin: 0 0 8px;
            overflow-wrap: anywhere;
        }

        .favorite-card p strong { color: var(--ink-2); }

        .favorite-card a.maps {
            color: var(--red);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .favorite-card a.maps:hover { text-decoration: underline; }

        .card-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: auto;
            padding-top: 16px;
        }

        .small-btn {
            display: inline-block;
            padding: 9px 14px;
            border: 1px solid rgba(15, 23, 42, .1);
            border-radius: 11px;
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s, transform .15s;
        }

        .small-btn:hover { background: #f1f5f9; transform: translateY(-1px); }

        .small-btn.delete { color: var(--red); }

        .small-btn.delete:hover {
            background: var(--red-soft);
            border-color: #fecdd3;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 40px 20px;
            background: rgba(248, 250, 252, .8);
            border: 2px dashed #e2e8f0;
            border-radius: 20px;
            line-height: 1.75;
            font-size: 14.5px;
        }

        .empty-icon { font-size: 44px; margin-bottom: 6px; }

        .fav-badges {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin: -2px 0 10px;
        }

        .fav-rating {
            padding: 3px 10px;
            border-radius: 8px;
            background: #fef3c7;
            color: #92400e;
            font-size: 12.5px;
            font-weight: 800;
        }

        .fav-type {
            padding: 3px 10px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #475569;
            font-size: 12.5px;
            font-weight: 600;
        }

        form { margin: 0; }

        /* ===== PENCAPAIAN ===== */
        .achievement-level {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 22px;
            border-radius: 22px;
            background:
                radial-gradient(circle at 90% 0%, rgba(251, 146, 60, .22), transparent 50%),
                linear-gradient(135deg, #fff1f2, #fff7ed);
            border: 1px solid rgba(244, 63, 94, .2);
        }

        .level-ring {
            --p: 0;
            flex: none;
            position: relative;
            width: 92px;
            height: 92px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: conic-gradient(var(--red) calc(var(--p) * 1%), rgba(244, 63, 94, .15) 0);
        }

        .level-ring::before {
            content: "";
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: #fff;
            box-shadow: inset 0 2px 6px rgba(15, 23, 42, .06);
        }

        .level-icon {
            position: relative;
            font-size: 36px;
            line-height: 1;
        }

        .level-body { flex: 1; min-width: 0; }

        .level-tag {
            display: inline-block;
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--red);
            margin-bottom: 2px;
        }

        .level-name { font-size: 22px; font-weight: 800; letter-spacing: -.02em; }
        .level-hint { font-size: 13.5px; color: var(--muted); margin-top: 2px; }

        .progress-bar {
            height: 10px;
            margin-top: 12px;
            border-radius: 999px;
            background: rgba(244, 63, 94, .15);
            overflow: hidden;
        }

        .progress-bar span {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: var(--grad);
            box-shadow: 0 0 12px rgba(244, 63, 94, .5);
        }

        .progress-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
            margin-top: 6px;
        }

        .achievement-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-top: 16px;
        }

        .badge-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .badge {
            text-align: center;
            padding: 18px 10px;
            border-radius: 20px;
            border: 1px solid var(--line);
            background: #fff;
            transition: transform .2s;
        }

        .badge:hover { transform: translateY(-3px); }

        .badge .b-icon { font-size: 30px; }
        .badge .b-name { font-size: 13px; font-weight: 800; margin-top: 6px; }
        .badge .b-req { font-size: 12px; color: var(--muted); margin-top: 2px; }

        .badge.locked { background: #f8fafc; }
        .badge.locked .b-icon { filter: grayscale(1); opacity: .35; }
        .badge.locked .b-name { color: var(--muted); }

        .badge.earned {
            border-color: rgba(244, 63, 94, .3);
            background: linear-gradient(180deg, #fff, #fff1f2);
            box-shadow: 0 8px 22px rgba(244, 63, 94, .14);
        }

        .done-list { margin-top: 24px; }
        .done-list h3 { font-size: 16px; font-weight: 800; margin: 0 0 12px; }

        .done-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            margin-bottom: 8px;
            border: 1px solid var(--line);
            border-left: 4px solid var(--red);
            border-radius: 14px;
            background: #fff;
            font-size: 14px;
        }

        .done-item strong { overflow-wrap: anywhere; }
        .done-item span { color: var(--muted); font-size: 13px; white-space: nowrap; }

        /* ===== FOOTER ACTIONS ===== */
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 30px;
            justify-content: center;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 760px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 640px) {
            .container { padding: 14px 12px 44px; }

            .cover { height: 140px; }

            .head {
                padding: 0 20px;
                margin-top: -60px;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .avatar { width: 112px; height: 112px; }
            .chips { justify-content: center; }
            .head-action { padding-bottom: 0; }

            h1 { font-size: 25px; }

            .bio { margin: 20px 20px 0; font-size: 15px; padding: 16px 18px 16px 48px; }

            .stats { padding: 20px; gap: 10px; }
            .stat { padding: 14px; }
            .stat-icon { width: 40px; height: 40px; font-size: 18px; margin-bottom: 10px; }
            .num { font-size: 26px; }
            .stat-label { font-size: 12px; }

            .menu { grid-template-columns: 1fr 1fr; }
            .item { padding: 12px; font-size: 13.5px; gap: 10px; }
            .item-icon { width: 34px; height: 34px; font-size: 15px; }
            .item-arrow { display: none; }

            .friend-section,
            .favorite-section,
            .achievement-section { padding: 22px 16px 26px; border-radius: 22px; }

            .favorite-heading h2 { font-size: 19px; }
            .favorite-list { grid-template-columns: 1fr; }

            .achievement-level { flex-direction: column; text-align: center; }
            .done-item { flex-direction: column; align-items: flex-start; gap: 2px; }
        }
    </style>
</head>

@php
    // ===== Teman =====
    // Butuh: trait HasFriends di model User + tabel friendships.
    $friendCount        = $user->friends()->count();
    $friendPreview      = $user->friends()->latest()->limit(7)->get();
    $pendingFriendCount = $user->receivedRequests()->where('status', 'pending')->count();

    // ===== Destinasi favorit =====
    // Dibaca langsung dari database, sehingga favorit dari tombol hati di peta
    // dan favorit manual sama-sama tampil otomatis.
    $favoritePlaces = $user->favoritePlaces()->latest()->get();
    $favoriteCount  = $favoritePlaces->count();

    // ===== Pencapaian penjelajah =====
    // Dihitung dari rencana perjalanan yang sudah ditandai selesai.
    $completedTrips = \App\Models\TravelPlan::where('user_id', $user->id)
        ->whereNotNull('completed_at')
        ->withCount('places')
        ->latest('completed_at')
        ->get();

    $completedCount = $completedTrips->count();
    $visitedTotal   = (int) $completedTrips->sum('places_count');

    // [syarat jumlah perjalanan selesai, ikon, nama level]
    $levels = [
        [0,  '🌱', 'Penjelajah Pemula'],
        [1,  '🧭', 'Penjelajah'],
        [3,  '🥾', 'Petualang'],
        [5,  '🏔️', 'Penjelajah Handal'],
        [10, '🏆', 'Legenda Nusantara'],
    ];

    $currentLevel = collect($levels)->last(fn ($l) => $completedCount >= $l[0]);
    $nextLevel    = collect($levels)->first(fn ($l) => $completedCount < $l[0]);

    $levelProgress = $nextLevel
        ? (int) round(($completedCount - $currentLevel[0]) / ($nextLevel[0] - $currentLevel[0]) * 100)
        : 100;

    // ===== Sampul profil =====
    $coverType = $user->cover_type ?? 'default';
    $coverSrc  = $user->cover_path ? asset('storage/' . $user->cover_path) : null;

    if (in_array($coverType, ['image', 'video']) && !$coverSrc) {
        $coverType = 'default';
    }

    $coverColor1 = $user->cover_color ?: '#dc2626';
    $coverColor2 = $user->cover_color2 ?: '#f97316';

    // Posisi & zoom foto/video sampul (diatur di halaman edit profil).
    $coverPosX = max(0, min(100, (float) ($user->cover_pos_x ?? 50)));
    $coverPosY = max(0, min(100, (float) ($user->cover_pos_y ?? 50)));
    $coverZoom = max(1, min(3, (float) ($user->cover_zoom ?? 1)));

    $coverMediaStyle = sprintf(
        'object-position: %s%% %s%%; transform-origin: %s%% %s%%; transform: scale(%s);',
        rtrim(rtrim(number_format($coverPosX, 2, '.', ''), '0'), '.'),
        rtrim(rtrim(number_format($coverPosY, 2, '.', ''), '0'), '.'),
        rtrim(rtrim(number_format($coverPosX, 2, '.', ''), '0'), '.'),
        rtrim(rtrim(number_format($coverPosY, 2, '.', ''), '0'), '.'),
        rtrim(rtrim(number_format($coverZoom, 2, '.', ''), '0'), '.')
    );

    // ===== Warna tulisan nama & username =====
    $nameColor = $user->name_color;
    $userColor = $user->username_color;

    // ===== Background halaman profil =====
    $bgType = $user->bg_type ?? 'default';
    $bgSrc  = $user->bg_path ? asset('storage/' . $user->bg_path) : null;
    $bgC1   = $user->bg_color ?: '#f6f7fb';
    $bgC2   = $user->bg_color2 ?: '#fde68a';

    // Gambar latar salju (taruh di public/images/bg/salju-bg.png)
    $bgSnowUrl = asset('images/bg/salju-bg.png');

    $pageBgStyle = '';
    if ($bgType === 'color') {
        $pageBgStyle = "background: {$bgC1};";
    } elseif ($bgType === 'gradient') {
        $pageBgStyle = "background: linear-gradient(135deg, {$bgC1}, {$bgC2});";
    } elseif ($bgType === 'image' && $bgSrc) {
        $pageBgStyle = "background: #111827 url('{$bgSrc}') center / cover no-repeat;";
    } elseif ($bgType === 'salju') {
        $pageBgStyle = "background: linear-gradient(180deg, #8fbcf0 0%, #a9cdf5 50%, #c3dcf8 100%);";
    }
@endphp

<body>
<div class="page-bg" style="{{ $pageBgStyle }}"></div>

@if($bgType === 'salju')
    {{-- Hiasan salju di pinggir layar --}}
    <div class="snow-side left"></div>
    <div class="snow-side right"></div>
    <div class="snow-frame top" style="background-image: url('{{ $bgSnowUrl }}')"></div>
    <div class="snow-frame bottom" style="background-image: url('{{ $bgSnowUrl }}')"></div>

    {{-- Salju jatuh --}}
    <canvas class="snow-canvas" id="snowCanvas"></canvas>
@endif

<main class="container">

    @if(session('success'))
        <div class="success anim">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="errors anim">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-card glass anim">

        {{-- SAMPUL PROFIL --}}
        <div class="cover {{ $coverType === 'gradient' ? 'is-gradient' : ($coverType === 'default' ? 'is-default' : 'is-media') }}"
             @if($coverType === 'gradient')
                 style="background: linear-gradient(120deg, {{ $coverColor1 }}, {{ $coverColor2 }});"
             @endif>

            @if($coverType === 'image')
                <img src="{{ $coverSrc }}" alt="Sampul profil" style="{{ $coverMediaStyle }}">
            @elseif($coverType === 'video')
                <video src="{{ $coverSrc }}" autoplay muted loop playsinline style="{{ $coverMediaStyle }}"></video>
            @endif
        </div>

        {{-- INFORMASI PROFIL --}}
        <section class="head">
            <div class="avatar-wrap">
                @if($user->avatar_path)
                    <img
                        class="avatar"
                        src="{{ asset('storage/' . $user->avatar_path) }}"
                        alt="Foto profil"
                    >
                @else
                    <div class="avatar avatar-fallback">🧳</div>
                @endif
            </div>

            <div class="info">
                <h1 @if($nameColor) style="color: {{ $nameColor }} !important;" @endif>{{ $user->name }}</h1>

                <div class="username" @if($userColor) style="color: {{ $userColor }} !important;" @endif>
                    @ {{ $user->username ?: \Illuminate\Support\Str::slug($user->name, '_') }}
                </div>

                <div class="chips">
                    <span class="chip">
                        📍 {{ $user->domicile ?: 'Domisili belum ditambahkan' }}
                    </span>

                    <span class="chip">
                        📅 Bergabung sejak
                        {{ $user->created_at?->translatedFormat('F Y') ?? '-' }}
                    </span>

                    <span class="chip">
                        👥 {{ $friendCount }} teman
                    </span>
                </div>
            </div>

            <div class="head-action">
                {{-- Icon tambah teman --}}
                <a class="btn icon-only"
                   href="{{ route('friends.index') }}"
                   title="Tambah teman"
                   aria-label="Tambah teman{{ $pendingFriendCount ? ' (' . $pendingFriendCount . ' permintaan baru)' : '' }}">
                    <svg class="ico" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <path d="M20 8v6M23 11h-6"/>
                    </svg>
                    @if($pendingFriendCount)
                        <span class="notif">{{ $pendingFriendCount }}</span>
                    @endif
                </a>

                <a class="btn red" href="{{ route('profile.edit') }}">
                    ✎ Edit Profil
                </a>
            </div>
        </section>

        <p class="bio">
            {{ $user->bio ?: 'Jelajahi keindahan Indonesia dan simpan perjalanan favoritmu.' }}
        </p>

        {{-- STATISTIK --}}
        <section class="stats">
            <div class="stat">
                <div class="stat-icon">📍</div>
                <div class="num" data-count="{{ $favoriteCount }}">{{ $favoriteCount }}</div>
                <div class="stat-label">Wisata tersimpan</div>
            </div>

            <div class="stat">
                <div class="stat-icon">♡</div>
                <div class="num" data-count="{{ $favoriteCount }}">{{ $favoriteCount }}</div>
                <div class="stat-label">Favorit</div>
            </div>

            <div class="stat">
                <div class="stat-icon">🧭</div>
                <div class="num" data-count="{{ $tripCount }}">{{ $tripCount }}</div>
                <div class="stat-label">Rencana perjalanan</div>
            </div>

            <a class="stat" href="{{ route('friends.index') }}">
                <div class="stat-icon">👥</div>
                <div class="num" data-count="{{ $friendCount }}">{{ $friendCount }}</div>
                <div class="stat-label">Teman</div>
            </a>
        </section>

    </div>

    {{-- MENU PROFIL --}}
    <nav class="menu anim d1">
        <a class="item" href="{{ route('profile.edit') }}">
            <span class="item-icon">♙</span>
            Informasi pribadi
            <span class="item-arrow">›</span>
        </a>

        <a class="item" href="{{ route('friends.index') }}">
            <span class="item-icon">
                <svg class="ico" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <path d="M20 8v6M23 11h-6"/>
                </svg>
            </span>
            Teman
            @if($pendingFriendCount)
                <span class="notif">{{ $pendingFriendCount }}</span>
            @endif
            <span class="item-arrow">›</span>
        </a>

        <a class="item" href="#destinasi-favorit">
            <span class="item-icon">♡</span>
            Wisata favorit
            <span class="item-arrow">›</span>
        </a>

        <a class="item" href="{{ route('trips.index') }}">
            <span class="item-icon">▦</span>
            Rencana perjalanan
            <span class="item-arrow">›</span>
        </a>

        <a class="item" href="#pencapaian">
            <span class="item-icon">🏆</span>
            Pencapaian penjelajah
            <span class="item-arrow">›</span>
        </a>
    </nav>

    {{-- TEMAN --}}
    <section class="friend-section glass anim d2" id="teman">
        <div class="favorite-heading">
            <h2><span class="h-ico">👥</span> Teman Saya</h2>

            <a class="btn red" href="{{ route('friends.index') }}">
                <svg class="ico" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M15 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <path d="M20 8v6M23 11h-6"/>
                </svg>
                Cari &amp; Tambah Teman
            </a>
        </div>

        @if($friendPreview->isNotEmpty())
            <div class="friend-strip">
                @foreach($friendPreview as $friend)
                    <div class="friend-chip">
                        @if($friend->avatar_path)
                            <img class="friend-avatar" src="{{ asset('storage/' . $friend->avatar_path) }}" alt="Foto {{ $friend->name }}">
                        @else
                            <div class="friend-avatar">{{ mb_strtoupper(mb_substr($friend->name ?: '?', 0, 1)) }}</div>
                        @endif
                        <span>{{ \Illuminate\Support\Str::limit($friend->name, 14) }}
                            <small>@ {{ $friend->username ?: \Illuminate\Support\Str::slug($friend->name, '_') }}</small>
                        </span>
                    </div>
                @endforeach

                <a class="friend-chip friend-add" href="{{ route('friends.index') }}">
                    <div class="friend-avatar">{{ $friendCount > 7 ? '+' . ($friendCount - 7) : '＋' }}</div>
                    <span>{{ $friendCount > 7 ? 'Lihat semua' : 'Tambah' }}</span>
                </a>
            </div>
        @else
            <div class="empty">
                <div class="empty-icon">🤝</div>
                Kamu belum punya teman.<br>
                Tekan tombol <strong>Cari &amp; Tambah Teman</strong>, lalu cari lewat username atau nama profilnya.
            </div>
        @endif
    </section>

    {{-- DAFTAR FAVORIT MANUAL --}}
    <section class="favorite-section glass anim d3" id="destinasi-favorit">
        <div class="favorite-heading">
            <h2><span class="h-ico">♡</span> Destinasi Favorit Saya</h2>

            <a class="btn red" href="{{ route('favorites.create') }}">
                + Tambah Favorit
            </a>
        </div>

        @if(isset($favoritePlaces) && $favoritePlaces->isNotEmpty())
            <div class="favorite-list">
                @foreach($favoritePlaces as $place)
                    @php
                        $attrs = $place->getAttributes();

                        // Tautan peta: kolom lama "url" atau "google_maps_url" dari tombol hati
                        $mapsLink = $attrs['url'] ?? ($attrs['google_maps_url'] ?? null);

                        $hasCoords = isset($attrs['latitude'], $attrs['longitude'])
                            && $attrs['latitude'] !== null
                            && $attrs['longitude'] !== null;

                        $routeUrl = $hasCoords
                            ? 'https://www.google.com/maps/dir/?api=1&destination=' . $attrs['latitude'] . ',' . $attrs['longitude']
                            : null;

                        $categoryLabel = match (strtolower((string) $place->category)) {
                            'wisata'  => '🏞️ Wisata',
                            'kuliner' => '🍜 Kuliner',
                            ''        => 'Lainnya',
                            default   => ucfirst((string) $place->category),
                        };

                        $lokasi = collect([$attrs['city'] ?? null, $attrs['province'] ?? null])
                            ->filter()
                            ->implode(', ');

                        $ratingValue = $attrs['rating'] ?? null;
                        $jenis       = $attrs['type'] ?? null;
                    @endphp

                    <article class="favorite-card">

                        <span class="category">
                            {{ $categoryLabel }}
                        </span>

                        <h3>♡ {{ $place->name }}</h3>

                        @if($ratingValue || $jenis)
                            <div class="fav-badges">
                                @if($ratingValue)
                                    <span class="fav-rating">⭐ {{ number_format((float) $ratingValue, 1) }}</span>
                                @endif

                                @if($jenis)
                                    <span class="fav-type">{{ $jenis }}</span>
                                @endif
                            </div>
                        @endif

                        @if($lokasi)
                            <p><strong>{{ $lokasi }}</strong></p>
                        @endif

                        @if($place->address)
                            <p>📍 {{ $place->address }}</p>
                        @endif

                        @if($attrs['description'] ?? null)
                            <p>{{ $attrs['description'] }}</p>
                        @endif

                        @if($mapsLink)
                            <p>
                                <a class="maps"
                                   href="{{ $mapsLink }}"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Lihat di Google Maps ↗
                                </a>
                            </p>
                        @endif

                        <div class="card-actions">
                            @if($routeUrl)
                                <a class="small-btn"
                                   href="{{ $routeUrl }}"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    🚗 Rute
                                </a>
                            @endif

                            <a class="small-btn"
                               href="{{ route('favorites.edit', $place->id) }}">
                                ✎ Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('favorites.destroy', $place->id) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus destinasi ini dari favorit?')">
                                @csrf
                                @method('DELETE')

                                <button class="small-btn delete" type="submit">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </article>
                @endforeach
            </div>

            @if(method_exists($favoritePlaces, 'links'))
                <div style="margin-top:22px">
                    {{ $favoritePlaces->links() }}
                </div>
            @endif
        @else
            <div class="empty">
                <div class="empty-icon">🗺️</div>
                Kamu belum memiliki destinasi favorit.<br>
                Tekan tombol ♡ pada tempat di peta, atau tambahkan secara manual dengan tombol
                <strong>Tambah Favorit</strong> di atas.
            </div>
        @endif
    </section>

    {{-- PENCAPAIAN PENJELAJAH --}}
    <section class="achievement-section glass anim d4" id="pencapaian">
        <div class="favorite-heading">
            <h2><span class="h-ico">🏆</span> Pencapaian Penjelajah</h2>

            <a class="btn" href="{{ route('achievements.index') }}">Lihat semua ›</a>
        </div>

        {{-- Level --}}
        <div class="achievement-level">
            <div class="level-ring" style="--p: {{ $levelProgress }}">
                <span class="level-icon">{{ $currentLevel[1] }}</span>
            </div>

            <div class="level-body">
                <span class="level-tag">Level saat ini</span>
                <div class="level-name">{{ $currentLevel[2] }}</div>
                <div class="level-hint">
                    @if($nextLevel)
                        {{ $nextLevel[0] - $completedCount }} perjalanan lagi menuju {{ $nextLevel[2] }}
                    @else
                        Level tertinggi tercapai. Luar biasa!
                    @endif
                </div>
                <div class="progress-bar"><span style="width: {{ $levelProgress }}%"></span></div>
                <div class="progress-meta">
                    <span>{{ $completedCount }} perjalanan</span>
                    <span>{{ $levelProgress }}%</span>
                </div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="achievement-stats">
            <div class="stat">
                <div class="stat-icon">✅</div>
                <div class="num" data-count="{{ $completedCount }}">{{ $completedCount }}</div>
                <div class="stat-label">Perjalanan selesai</div>
            </div>

            <div class="stat">
                <div class="stat-icon">📍</div>
                <div class="num" data-count="{{ $visitedTotal }}">{{ $visitedTotal }}</div>
                <div class="stat-label">Tempat dikunjungi</div>
            </div>
        </div>

        {{-- Lencana --}}
        <div class="badge-grid">
            @foreach($levels as $lv)
                @continue($lv[0] === 0)
                <div class="badge {{ $completedCount >= $lv[0] ? 'earned' : 'locked' }}">
                    <div class="b-icon">{{ $lv[1] }}</div>
                    <div class="b-name">{{ $lv[2] }}</div>
                    <div class="b-req">{{ $lv[0] }} perjalanan</div>
                </div>
            @endforeach
        </div>

        {{-- Perjalanan terakhir yang selesai --}}
        @if($completedTrips->isNotEmpty())
            <div class="done-list">
                <h3>Perjalanan selesai terbaru</h3>

                @foreach($completedTrips->take(3) as $trip)
                    <div class="done-item">
                        <strong>🧭 {{ $trip->title }}</strong>
                        <span>
                            {{ $trip->places_count }} tempat ·
                            {{ \Illuminate\Support\Carbon::parse($trip->completed_at)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty" style="margin-top:20px">
                <div class="empty-icon">🎒</div>
                Belum ada perjalanan yang selesai.<br>
                Tandai semua tujuan di rencana perjalananmu sebagai dikunjungi untuk membuka pencapaian pertamamu.
            </div>
        @endif
    </section>

    {{-- TOMBOL AKSI --}}
    <div class="actions anim d5">
        <a class="btn" href="{{ route('map.index') }}">
            ← Kembali ke Peta
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" type="submit">Keluar</button>
        </form>
    </div>

</main>

{{-- Animasi hitung angka statistik --}}
<script>
    (function () {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        document.querySelectorAll('.num[data-count]').forEach(function (el) {
            var target = parseInt(el.getAttribute('data-count'), 10);
            if (!target || target < 1) return;

            var start = null, duration = 900;

            function step(ts) {
                if (start === null) start = ts;
                var p = Math.min((ts - start) / duration, 1);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(target * eased);
                if (p < 1) requestAnimationFrame(step);
            }

            el.textContent = '0';
            requestAnimationFrame(step);
        });
    })();
</script>

@if($bgType === 'salju')
    <script src="{{ asset('js/snow-fx.js') }}"></script>
    <script>
        SnowFX(document.getElementById('snowCanvas'), { density: 9000, min: 40, max: 140, scale: 1 }).start();
    </script>
@endif
</body>
</html>