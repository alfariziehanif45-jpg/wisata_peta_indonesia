<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - Jelajah Wisata Indonesia</title>

    <style>
        :root {
            --red: #dc2626;
            --red-dark: #b91c1c;
            --red-soft: #fef2f2;
            --orange: #f97316;
            --ink: #1f2937;
            --muted: #6b7280;
            --line: #eceef2;
            --bg: #f6f7fb;
            --card: #ffffff;
            --shadow: 0 1px 2px rgba(16, 24, 40, .05), 0 8px 24px rgba(16, 24, 40, .06);
            --shadow-hover: 0 4px 8px rgba(16, 24, 40, .06), 0 16px 36px rgba(16, 24, 40, .12);
        }

        * { box-sizing: border-box; }

        html { background: var(--bg); }

        body {
            margin: 0;
            min-height: 100vh;
            background: transparent;
            color: var(--ink);
            font-family: "Inter", "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Lapisan background halaman profil (diatur dari halaman edit) */
        .page-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: var(--bg);
        }

        /* Lapisan partikel salju (hanya muncul bila background "Salju" dipilih).
           Berada di atas background, tetapi di bawah kartu agar tulisan tetap terbaca. */
        .snow-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        /* Hiasan salju di PINGGIR layar (atas, bawah, kiri, kanan).
           Gambar dipotong & dipudarkan dengan mask, sehingga bagian tengah tetap polos. */
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

        /* Kabut salju tipis di sisi kiri & kanan */
        .snow-side {
            top: 0;
            bottom: 0;
            width: clamp(40px, 8vw, 120px);
        }
        .snow-side.left  { left: 0;  background: linear-gradient(to right, rgba(255,255,255,.55), transparent); }
        .snow-side.right { right: 0; background: linear-gradient(to left,  rgba(255,255,255,.55), transparent); }

        .container {
            max-width: 1000px;
            margin: auto;
            padding: 24px 20px 60px;
        }

        /* ===== ALERT ===== */
        .success {
            padding: 14px 18px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .errors {
            padding: 14px 18px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .errors ul { margin: 8px 0 0; padding-left: 20px; }

        /* ===== PROFILE CARD ===== */
        .profile-card {
            background: var(--card);
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* ===== COVER (default / gradien / foto / video) ===== */
        .cover {
            height: 150px;
            position: relative;
            overflow: hidden;
            background: #111827;
        }

        .cover.is-default {
            background:
                radial-gradient(circle at 15% 20%, rgba(255,255,255,.25), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(255,255,255,.18), transparent 45%),
                linear-gradient(120deg, var(--red-dark), var(--red) 50%, var(--orange));
        }

        .cover.is-default::after,
        .cover.is-gradient::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.18) 1.5px, transparent 1.5px);
            background-size: 22px 22px;
            opacity: .6;
            pointer-events: none;
        }

        /* Foto/video sampul. Posisi & zoom dari halaman edit diterapkan lewat
           style inline (object-position + transform scale + transform-origin). */
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

        .head {
            display: flex;
            gap: 22px;
            align-items: flex-end;
            flex-wrap: wrap;
            padding: 0 30px;
            margin-top: -64px;
            position: relative;
            z-index: 1;
        }

        .avatar {
            width: 128px;
            height: 128px;
            object-fit: cover;
            border: 5px solid #fff;
            border-radius: 50%;
            background: #fff7ed;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);
        }

        .avatar-fallback {
            display: grid;
            place-items: center;
            font-size: 52px;
            background: linear-gradient(135deg, #fff1f2, #ffedd5);
        }

        .info {
            flex: 1;
            min-width: 220px;
            padding-bottom: 6px;
        }

        h1 {
            font-size: 28px;
            line-height: 1.2;
            margin: 0 0 4px;
            letter-spacing: -.02em;
            color: var(--ink);
            overflow-wrap: anywhere;
        }

        .username {
            color: var(--red);
            font-weight: 600;
            font-size: 15px;
            overflow-wrap: anywhere;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #f3f4f6;
            color: #4b5563;
            border-radius: 999px;
            font-size: 13px;
        }

        .head-action { padding-bottom: 10px; }

        .bio {
            margin: 24px 30px 0;
            padding: 16px 20px;
            background: #fafafa;
            border-left: 4px solid var(--red);
            border-radius: 0 12px 12px 0;
            font-size: 16px;
            color: #4b5563;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        /* ===== STATS ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            padding: 24px 30px 30px;
        }

        .stat {
            text-align: center;
            padding: 18px 10px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: linear-gradient(180deg, #fff, #fafbfc);
            transition: transform .2s, box-shadow .2s;
        }

        .stat:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto 8px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: var(--red-soft);
            font-size: 20px;
        }

        .num {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1.1;
        }

        .stat-label {
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ===== MENU ===== */
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
            margin-top: 24px;
        }

        .item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            color: var(--ink);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            background: var(--card);
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: transform .2s, box-shadow .2s;
        }

        .item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .item-icon {
            flex: none;
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: var(--red-soft);
            font-size: 19px;
        }

        .item-arrow {
            margin-left: auto;
            color: #c4c7ce;
            transition: transform .2s, color .2s;
        }

        .item:hover .item-arrow {
            transform: translateX(3px);
            color: var(--red);
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid #e0e3e8;
            color: var(--ink);
            background: #fff;
            cursor: pointer;
            font-family: inherit;
            transition: transform .15s, box-shadow .15s, background .15s;
        }

        .btn:hover {
            transform: translateY(-1px);
            background: #f9fafb;
        }

        .btn.red {
            background: linear-gradient(135deg, var(--red), var(--red-dark));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 6px 16px rgba(220, 38, 38, .3);
        }

        .btn.red:hover {
            background: linear-gradient(135deg, #ef4444, var(--red));
            box-shadow: 0 8px 20px rgba(220, 38, 38, .38);
        }

        /* ===== FAVORITES ===== */
        .favorite-section {
            margin-top: 34px;
            background: var(--card);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 26px 30px 30px;
        }

        .favorite-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .favorite-heading h2 {
            font-size: 21px;
            margin: 0;
            letter-spacing: -.01em;
        }

        .favorite-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .favorite-card {
            display: flex;
            flex-direction: column;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 18px;
            overflow-wrap: anywhere;
            background: #fff;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }

        .favorite-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--red), var(--orange));
        }

        .favorite-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: #fecaca;
        }

        .category {
            align-self: flex-start;
            padding: 5px 11px;
            border-radius: 999px;
            background: var(--red-soft);
            color: var(--red-dark);
            font-size: 12px;
            font-weight: 700;
            margin: 6px 0 12px;
        }

        .favorite-card h3 {
            margin: 0 0 10px;
            font-size: 18px;
            line-height: 1.3;
        }

        .favorite-card p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            margin: 0 0 8px;
            overflow-wrap: anywhere;
        }

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
            padding: 8px 13px;
            border: 1px solid #e0e3e8;
            border-radius: 9px;
            background: #fff;
            color: var(--ink);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .small-btn:hover { background: #f3f4f6; }

        .small-btn.delete { color: var(--red); }

        .small-btn.delete:hover {
            background: var(--red-soft);
            border-color: #fecaca;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 36px 20px;
            background: #fafafa;
            border: 2px dashed #e5e7eb;
            border-radius: 16px;
            line-height: 1.7;
        }

        .empty-icon { font-size: 40px; margin-bottom: 6px; }

        /* Info tambahan kartu favorit (rating, jenis, lokasi) */
        .fav-badges {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin: -4px 0 10px;
        }

        .fav-rating {
            padding: 3px 9px;
            border-radius: 7px;
            background: #fef3c7;
            color: #92400e;
            font-size: 12.5px;
            font-weight: 700;
        }

        .fav-type {
            padding: 3px 9px;
            border-radius: 7px;
            background: #f3f4f6;
            color: #4b5563;
            font-size: 12.5px;
            font-weight: 600;
        }

        form { margin: 0; }

        /* ===== PENCAPAIAN ===== */
        .achievement-section {
            margin-top: 34px;
            background: var(--card);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 26px 30px 30px;
        }

        .achievement-level {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--red-soft), #fff7ed);
            border: 1px solid #fecaca;
        }

        .level-icon {
            flex: none;
            width: 60px;
            height: 60px;
            display: grid;
            place-items: center;
            font-size: 30px;
            border-radius: 18px;
            background: #fff;
            box-shadow: var(--shadow);
        }

        .level-body { flex: 1; min-width: 0; }
        .level-name { font-size: 18px; font-weight: 800; letter-spacing: -.01em; }
        .level-hint { font-size: 13px; color: var(--muted); margin-top: 2px; }

        .progress-bar {
            height: 8px;
            margin-top: 10px;
            border-radius: 999px;
            background: #fee2e2;
            overflow: hidden;
        }

        .progress-bar span {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--red), var(--orange));
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
            margin-top: 20px;
        }

        .badge {
            text-align: center;
            padding: 16px 10px;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: #fff;
        }

        .badge .b-icon { font-size: 28px; }
        .badge .b-name { font-size: 13px; font-weight: 700; margin-top: 6px; }
        .badge .b-req { font-size: 12px; color: var(--muted); margin-top: 2px; }

        .badge.locked { background: #f9fafb; }
        .badge.locked .b-icon { filter: grayscale(1); opacity: .35; }
        .badge.locked .b-name { color: var(--muted); }

        .badge.earned {
            border-color: #fecaca;
            background: linear-gradient(180deg, #fff, var(--red-soft));
        }

        .done-list { margin-top: 22px; }
        .done-list h3 { font-size: 16px; margin: 0 0 12px; }

        .done-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 8px;
            border: 1px solid var(--line);
            border-radius: 12px;
            font-size: 14px;
        }

        .done-item strong { overflow-wrap: anywhere; }
        .done-item span { color: var(--muted); font-size: 13px; white-space: nowrap; }

        /* ===== FOOTER ACTIONS ===== */
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 28px;
            justify-content: center;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .container { padding: 14px 12px 40px; }

            .cover { height: 110px; }

            .head {
                padding: 0 20px;
                margin-top: -54px;
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .avatar { width: 108px; height: 108px; }
            .chips { justify-content: center; }
            .head-action { padding-bottom: 0; }

            h1 { font-size: 23px; }

            .bio { margin: 20px 20px 0; font-size: 15px; }

            .stats { padding: 20px; gap: 8px; }
            .stat { padding: 14px 6px; }
            .stat-icon { width: 38px; height: 38px; font-size: 17px; }
            .num { font-size: 24px; }
            .stat-label { font-size: 12px; }

            .favorite-section { padding: 20px 16px 24px; border-radius: 20px; }
            .favorite-heading h2 { font-size: 19px; }
            .favorite-list { grid-template-columns: 1fr; }

            .achievement-section { padding: 20px 16px 24px; border-radius: 20px; }
            .done-item { flex-direction: column; align-items: flex-start; gap: 2px; }
        }
    </style>
</head>

@php
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
    // x,y dalam persen (0-100), zoom 1-3. Nilai dibersihkan agar aman dipakai di CSS.
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
        // Dasar polos biru es lembut; hiasan salju ditaruh di pinggir (lihat .snow-frame)
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
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="errors">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-card">

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
            <div>
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
                </div>
            </div>

            <div class="head-action">
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
                <div class="num">{{ $favoriteCount }}</div>
                <div class="stat-label">Wisata tersimpan</div>
            </div>

            <div class="stat">
                <div class="stat-icon">♡</div>
                <div class="num">{{ $favoriteCount }}</div>
                <div class="stat-label">Favorit</div>
            </div>

            <div class="stat">
                <div class="stat-icon">🧭</div>
                <div class="num">{{ $tripCount }}</div>
                <div class="stat-label">Rencana perjalanan</div>
            </div>
        </section>

    </div>

    {{-- MENU PROFIL --}}
    <nav class="menu">
        <a class="item" href="{{ route('profile.edit') }}">
            <span class="item-icon">♙</span>
            Informasi pribadi
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

    {{-- DAFTAR FAVORIT MANUAL --}}
    <section class="favorite-section" id="destinasi-favorit">
        <div class="favorite-heading">
            <h2>♡ Destinasi Favorit Saya</h2>

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
    <section class="achievement-section" id="pencapaian">
        <div class="favorite-heading">
            <h2>🏆 Pencapaian Penjelajah</h2>

            <a class="btn" href="{{ route('achievements.index') }}">Lihat semua ›</a>
        </div>

        {{-- Level --}}
        <div class="achievement-level">
            <div class="level-icon">{{ $currentLevel[1] }}</div>

            <div class="level-body">
                <div class="level-name">{{ $currentLevel[2] }}</div>
                <div class="level-hint">
                    @if($nextLevel)
                        {{ $nextLevel[0] - $completedCount }} perjalanan lagi menuju {{ $nextLevel[2] }}
                    @else
                        Level tertinggi tercapai. Luar biasa!
                    @endif
                </div>
                <div class="progress-bar"><span style="width: {{ $levelProgress }}%"></span></div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="achievement-stats">
            <div class="stat">
                <div class="stat-icon">✅</div>
                <div class="num">{{ $completedCount }}</div>
                <div class="stat-label">Perjalanan selesai</div>
            </div>

            <div class="stat">
                <div class="stat-icon">📍</div>
                <div class="num">{{ $visitedTotal }}</div>
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
    <div class="actions">
        <a class="btn" href="{{ route('map.index') }}">
            ← Kembali ke Peta
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn" type="submit">Keluar</button>
        </form>
    </div>

</main>

@if($bgType === 'salju')
    <script src="{{ asset('js/snow-fx.js') }}"></script>
    <script>
        SnowFX(document.getElementById('snowCanvas'), { density: 9000, min: 40, max: 140, scale: 1 }).start();
    </script>
@endif
</body>
</html>