<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Jelajah Wisata Indonesia</title>


<!-- LEAFLET CSS -->
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html,
    body {
        width: 100%;
        height: 100%;
        overflow: hidden;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        background: #f1f5f9;
    }

    /* MAP */
    #map {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        min-height: 300px;
        background: #e5e7eb;
        z-index: 1;
    }

    /* Pastikan tile Leaflet berada di bawah kontrol */
    .leaflet-container {
        width: 100%;
        height: 100%;
        background: #e5e7eb;
        font-family: Arial, Helvetica, sans-serif;
    }

    .leaflet-tile {
        visibility: visible;
    }

    .leaflet-control-container {
        z-index: 500;
    }

    /* HEADER */
    .header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        padding: 20px 30px;
        background: linear-gradient(
            to bottom,
            rgba(0, 0, 0, .7),
            transparent
        );
        color: white;
        pointer-events: none;
    }

    .header h1 {
        font-size: 24px;
        text-shadow: 0 2px 6px rgba(0, 0, 0, .8);
    }

    /* SEARCH BUTTON */
    #searchToggle {
        position: absolute;
        top: 85px;
        right: 20px;
        z-index: 2000;
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 50%;
        background: white;
        font-size: 22px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .3);
    }

    #searchToggle:hover {
        transform: scale(1.08);
    }

    /* SEARCH PANEL */
    #searchPanel {
        display: none;
        position: absolute;
        top: 85px;
        right: 20px;
        width: 350px;
        max-height: 75vh;
        z-index: 2000;
        background: white;
        border-radius: 14px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .35);
        overflow: hidden;
    }

    .search-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        background: #111;
        color: white;
    }

    .search-header h3 {
        font-size: 17px;
    }

    #closeSearch {
        border: none;
        background: transparent;
        color: white;
        font-size: 24px;
        cursor: pointer;
    }

    /* SEARCH MODE */
    .search-mode {
        padding: 12px 16px;
        background: #f4f4f4;
        font-size: 13px;
        color: #555;
    }

    .search-mode strong {
        color: #111;
    }

    /* BACK */
    #backToProvince {
        display: none;
        width: calc(100% - 30px);
        margin: 12px 15px 0;
        padding: 10px;
        border: none;
        border-radius: 8px;
        background: #222;
        color: white;
        cursor: pointer;
        font-size: 13px;
        font-weight: 700;
    }

    /* SEARCH INPUT */
    .search-input-wrapper {
        padding: 15px;
    }

    #searchInput {
        width: 100%;
        padding: 13px 15px;
        border: 1px solid #ddd;
        border-radius: 9px;
        outline: none;
        font-size: 14px;
    }

    /* SEARCH RESULT */
    #searchResults {
        max-height: 45vh;
        overflow-y: auto;
    }

    .search-result {
        padding: 13px 16px;
        border-top: 1px solid #eee;
        cursor: pointer;
    }

    .search-result:hover {
        background: #f3f3f3;
    }

    .result-name {
        font-weight: bold;
        font-size: 14px;
    }

    .result-info {
        margin-top: 4px;
        font-size: 12px;
        color: #777;
    }

    .no-result {
        padding: 25px 15px;
        text-align: center;
        color: #777;
        font-size: 13px;
    }

    /* LOCATION INFO */
    #locationInfo {
        position: absolute;
        left: 20px;
        bottom: 25px;
        z-index: 1500;
        display: none;
        max-width: 430px;
        max-height: 82vh;
        overflow-y: auto;
        background: rgba(255, 255, 255, .97);
        padding: 15px 18px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .25);
    }

    #locationName {
        display: block;
        font-size: 17px;
        margin-bottom: 5px;
    }

    #locationAddress {
        display: block;
        font-size: 12px;
        color: #666;
        line-height: 1.5;
        margin-bottom: 13px;
    }

    /* CATEGORY BUTTON */
    #categoryOptions {
        display: none;
        gap: 8px;
        flex-wrap: wrap;
    }

    .category-btn {
        border: none;
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: bold;
        transition: .2s;
    }

    .category-btn.wisata {
        background: #2e7d32;
        color: white;
    }

    .category-btn.kuliner {
        background: #e65100;
        color: white;
    }

    .category-btn.hotel {
        background: #6a1b9a;
        color: white;
    }

    .category-btn.ibadah {
        background: #00838f;
        color: white;
    }

    .category-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, .2);
    }

    /* CLEAR PLACES */
    #clearPlaces {
        display: none;
        margin-top: 8px;
        padding: 7px 10px;
        border: none;
        border-radius: 6px;
        background: #eee;
        color: #444;
        cursor: pointer;
        font-size: 11px;
    }

    /* MARKER WISATA */
    .tourism-marker {
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        background: #2e7d32;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, .4);
        position: relative;
    }

    .tourism-marker::after {
        content: "★";
        position: absolute;
        color: white;
        font-size: 15px;
        top: 5px;
        left: 7px;
        transform: rotate(45deg);
    }

    /* MARKER KULINER */
    .food-marker {
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        background: #e65100;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, .4);
        position: relative;
    }

    .food-marker::after {
        content: "🍜";
        position: absolute;
        font-size: 14px;
        top: 5px;
        left: 5px;
        transform: rotate(45deg);
    }

    /* MARKER HOTEL */
    .hotel-marker {
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        background: #6a1b9a;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, .4);
        position: relative;
    }

    .hotel-marker::after {
        content: "🏨";
        position: absolute;
        font-size: 14px;
        top: 5px;
        left: 5px;
        transform: rotate(45deg);
    }

    /* MARKER TEMPAT IBADAH */
    .ibadah-marker {
        width: 34px;
        height: 34px;
        border-radius: 50% 50% 50% 0;
        background: #00838f;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, .4);
        position: relative;
    }

    .ibadah-marker::after {
        content: "🕌";
        position: absolute;
        font-size: 14px;
        top: 5px;
        left: 5px;
        transform: rotate(45deg);
    }

    /* PLACE POPUP */
    .place-popup-title {
        font-weight: bold;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .place-popup-type {
        font-size: 12px;
        color: #777;
        margin-bottom: 5px;
    }

    .place-popup-address {
        font-size: 11px;
        color: #555;
    }

    /* LOADING */
    #placesLoading {
        display: none;
        margin-top: 8px;
        font-size: 12px;
        color: #777;
    }

    /* =====================================================
       INFO PROVINSI + CUACA REAL TIME
    ====================================================== */
    #provinceInfo {
        display: none;
        margin-bottom: 12px;
    }

    .prov-section-title {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #64748b;
        margin: 0 0 6px;
    }

    .prov-summary {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .prov-summary img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        background: #e5e7eb;
    }

    .prov-summary-text {
        font-size: 12px;
        line-height: 1.55;
        color: #334155;
    }

    .prov-wiki-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
        padding: 4px 10px;
        border-radius: 7px;
        background: #e8f5e9;
        color: #198754;
        border: 1px solid #c8e6c9;
        font-size: 11.5px;
        font-weight: 700;
        text-decoration: none;
    }

    .weather-card {
        border-radius: 12px;
        padding: 12px 13px;
        color: #fff;
        background: linear-gradient(135deg, #0ea5e9, #2563eb);
    }

    .weather-card.night {
        background: linear-gradient(135deg, #1e293b, #4338ca);
    }

    .weather-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .weather-icon {
        font-size: 40px;
        line-height: 1;
    }

    .weather-temp {
        font-size: 32px;
        font-weight: 800;
        line-height: 1;
    }

    .weather-desc {
        font-size: 12.5px;
        font-weight: 700;
        margin-top: 3px;
    }

    .weather-minmax {
        font-size: 11px;
        opacity: .9;
        margin-top: 2px;
    }

    .weather-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        margin-top: 10px;
    }

    .weather-item {
        background: rgba(255, 255, 255, .18);
        border-radius: 8px;
        padding: 6px 7px;
        text-align: center;
    }

    .weather-item .w-label {
        font-size: 10px;
        opacity: .9;
    }

    .weather-item .w-value {
        font-size: 12.5px;
        font-weight: 800;
        margin-top: 2px;
    }

    .weather-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 9px;
        font-size: 10.5px;
        opacity: .92;
    }

    .weather-live-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 4px;
        border-radius: 50%;
        background: #4ade80;
        animation: weatherPulse 1.6s infinite;
    }

    @keyframes weatherPulse {
        0% { opacity: 1; }
        50% { opacity: .3; }
        100% { opacity: 1; }
    }

    .weather-refresh-btn {
        border: none;
        border-radius: 6px;
        padding: 3px 8px;
        background: rgba(255, 255, 255, .25);
        color: #fff;
        font-size: 10.5px;
        font-weight: 700;
        cursor: pointer;
    }

    .weather-loading,
    .weather-error {
        padding: 12px;
        border-radius: 10px;
        font-size: 12px;
        text-align: center;
    }

    .weather-loading {
        background: #f1f5f9;
        color: #64748b;
    }

    .weather-error {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* TOMBOL FAVORIT */
    .favorite-toggle-btn:disabled {
        opacity: .65;
        cursor: wait !important;
    }

    .favorite-toggle-btn.is-saved {
        background: #dc2626 !important;
        color: #fff !important;
        border-color: #dc2626 !important;
    }

    /* TOAST */
    #toast {
        display: none;
        position: fixed;
        top: 78px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 6000;
        max-width: 90vw;
        padding: 11px 16px;
        border-radius: 10px;
        background: #111827;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .35);
        align-items: center;
        gap: 12px;
    }

    #toast.show {
        display: flex;
    }

    #toast.error {
        background: #b91c1c;
    }

    #toast a {
        color: #fde68a;
        font-weight: 800;
        text-decoration: underline;
        white-space: nowrap;
    }

    /* =====================================================
       PANEL RUTE
    ====================================================== */
    #routePanel {
        display: none;
        position: absolute;
        right: 20px;
        bottom: 25px;
        width: 340px;
        max-height: 80vh;
        z-index: 2500;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, .35);
        overflow: hidden;
        flex-direction: column;
    }

    #routePanel.show {
        display: flex;
    }

    .route-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 13px 16px;
        background: #111827;
        color: #fff;
    }

    .route-header strong {
        font-size: 14px;
        line-height: 1.35;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    #routeClose {
        border: none;
        background: transparent;
        color: #fff;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        flex-shrink: 0;
    }

    .route-body {
        padding: 13px 14px 14px;
        overflow-y: auto;
    }

    .route-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 5px;
    }

    #routeOrigin {
        width: 100%;
        padding: 9px 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 12.5px;
        background: #fff;
        color: #1f2937;
        cursor: pointer;
        margin-bottom: 12px;
    }

    .route-modes {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        margin-bottom: 12px;
    }

    .route-mode-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        padding: 8px 4px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: .15s;
    }

    .route-mode-btn .route-mode-icon {
        font-size: 20px;
        line-height: 1;
    }

    .route-mode-btn:hover {
        background: #f8fafc;
    }

    .route-mode-btn.active {
        border-color: var(--mode-color, #2563eb);
        background: #eff6ff;
        color: #0f172a;
    }

    #routeSummary {
        margin-bottom: 10px;
    }

    .route-info-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 11px 13px;
        border-radius: 10px;
        background: #f1f5f9;
    }

    .route-info-time {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .route-info-dist {
        font-size: 12.5px;
        color: #475569;
        font-weight: 600;
        margin-top: 2px;
    }

    .route-info-icon {
        font-size: 30px;
    }

    .route-note {
        margin-top: 6px;
        font-size: 10.5px;
        color: #94a3b8;
        line-height: 1.4;
    }

    .route-msg {
        padding: 12px;
        border-radius: 10px;
        font-size: 12px;
        text-align: center;
        line-height: 1.5;
    }

    .route-msg.loading {
        background: #f1f5f9;
        color: #64748b;
    }

    .route-msg.error {
        background: #fee2e2;
        color: #b91c1c;
    }

    #routeSteps {
        max-height: 190px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
    }

    #routeSteps:empty {
        display: none;
    }

    .route-step {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        padding: 8px 11px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
        color: #334155;
        line-height: 1.4;
    }

    .route-step:last-child {
        border-bottom: none;
    }

    .route-step-dist {
        flex-shrink: 0;
        color: #64748b;
        font-weight: 700;
        font-size: 11px;
    }

    #routeGmaps {
        display: inline-block;
        margin-top: 10px;
        font-size: 11.5px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
    }

    #routeGmaps:hover {
        text-decoration: underline;
    }

    .route-origin-marker {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #2563eb;
        border: 3px solid #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, .3), 0 2px 8px rgba(0, 0, 0, .4);
    }

    /* RESPONSIVE */
    @media (max-width: 600px) {
        .header {
            padding: 15px 18px;
        }

        .header h1 {
            font-size: 18px;
        }

        #searchToggle {
            top: 70px;
            right: 15px;
        }

        #searchPanel {
            top: 70px;
            left: 15px;
            right: 15px;
            width: auto;
        }

        #locationInfo {
            left: 15px;
            right: 15px;
            bottom: 15px;
            max-width: none;
        }

        #routePanel {
            left: 15px;
            right: 15px;
            bottom: 15px;
            width: auto;
            max-height: 62vh;
            z-index: 3000;
        }
    }
</style>

</head>


<body>

    <!-- Akses akun: ditambahkan tanpa mengubah tampilan dan fungsi peta -->
    <div style="position:fixed;top:18px;right:20px;z-index:5000;display:flex;align-items:center;gap:8px;font-family:Arial,Helvetica,sans-serif">
        @auth
            <a href="{{ route('profile.show') }}" title="Profil Saya" style="display:inline-flex;align-items:center;gap:7px;text-decoration:none;border-radius:9px;padding:10px 14px;background:rgba(0,0,0,.78);color:#fff;font-size:13px;font-weight:700;border:1px solid rgba(255,255,255,.25)">
                <span aria-hidden="true">👤</span><span>Profil</span>
            </a>
            <span style="background:rgba(0,0,0,.72);color:#fff;padding:10px 13px;border-radius:9px;font-size:13px;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">Halo, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" style="border:0;border-radius:9px;padding:10px 14px;background:#fff;color:#17212b;font-weight:700;cursor:pointer">Keluar</button>
            </form>
        @else
            <a href="{{ route('login') }}" style="text-decoration:none;border-radius:9px;padding:10px 14px;background:rgba(0,0,0,.72);color:#fff;font-size:13px;font-weight:700">Masuk</a>
            <a href="{{ route('register') }}" style="text-decoration:none;border-radius:9px;padding:10px 14px;background:#fff;color:#17212b;font-size:13px;font-weight:700;box-shadow:0 3px 12px #0003">Daftar</a>
        @endauth
    </div>


    <!-- MAP -->
    <div id="map"></div>


    <!-- TOAST NOTIFIKASI -->
    <div id="toast" role="status" aria-live="polite"></div>


    <!-- PANEL RUTE -->
    <div id="routePanel">

        <div class="route-header">
            <strong id="routeTitle">🧭 Rute</strong>
            <button type="button" id="routeClose" title="Tutup rute">×</button>
        </div>

        <div class="route-body">

            <label class="route-label" for="routeOrigin">Titik awal</label>
            <select id="routeOrigin">
                <option value="me">📍 Lokasi saya</option>
                <option value="city">🏙️ Pusat kota yang dipilih</option>
            </select>

            <span class="route-label">Pilih kendaraan</span>
            <div class="route-modes" id="routeModes">
                <button type="button" class="route-mode-btn active" data-mode="car" style="--mode-color:#2563eb">
                    <span class="route-mode-icon">🚗</span><span>Mobil</span>
                </button>
                <button type="button" class="route-mode-btn" data-mode="motor" style="--mode-color:#ea580c">
                    <span class="route-mode-icon">🏍️</span><span>Motor</span>
                </button>
                <button type="button" class="route-mode-btn" data-mode="bike" style="--mode-color:#16a34a">
                    <span class="route-mode-icon">🚲</span><span>Sepeda</span>
                </button>
                <button type="button" class="route-mode-btn" data-mode="foot" style="--mode-color:#9333ea">
                    <span class="route-mode-icon">🚶</span><span>Jalan</span>
                </button>
            </div>

            <div id="routeSummary"></div>
            <div id="routeSteps"></div>

            <a id="routeGmaps" href="#" target="_blank" rel="noopener noreferrer">
                Buka di Google Maps ↗
            </a>

        </div>

    </div>


    <!-- SEARCH BUTTON -->
    <button id="searchToggle">🔍</button>


    <!-- SEARCH PANEL -->
    <div id="searchPanel">

        <div class="search-header">
            <h3 id="searchTitle">🔍 Cari Provinsi Indonesia</h3>
            <button id="closeSearch">×</button>
        </div>

        <div class="search-mode">
            Mode:
            <strong id="searchMode">Indonesia</strong>
        </div>

        <button id="backToProvince" style="display:none;">← Kembali</button>

        <div class="search-input-wrapper">
            <input
                type="text"
                id="searchInput"
                placeholder="Cari provinsi Indonesia..."
                autocomplete="off"
            >
        </div>

        <div id="searchResults"></div>

    </div>


    <!-- LOCATION INFO -->
    <div id="locationInfo">

        <strong id="locationName"></strong>

        <span id="locationAddress"></span>

        <!-- INFO PROVINSI + CUACA REAL TIME -->
        <div id="provinceInfo">

            <div class="prov-section-title">ℹ️ Informasi Provinsi</div>
            <div id="provinceSummary"></div>

            <div class="prov-section-title">🌦️ Cuaca Saat Ini</div>
            <div id="provinceWeather"></div>

        </div>

        <div id="cityWikiContainer" style="display:none;margin-top:8px;margin-bottom:8px;">
            <a
                id="cityWikiLink"
                href="#"
                target="_blank"
                rel="noopener noreferrer"
                style="
                    display:inline-flex;
                    align-items:center;
                    gap:6px;
                    background:#e8f5e9;
                    color:#198754;
                    border:1px solid #c8e6c9;
                    padding:5px 11px;
                    border-radius:8px;
                    font-size:12px;
                    font-weight:600;
                    text-decoration:none;
                    transition:all .2s ease;
                "
            >
                🌐 Buka Wikipedia <span id="cityWikiLabel"></span> &nearr;
            </a>
        </div>


        <!-- CATEGORY -->
        <div id="categoryOptions">

            <button type="button" class="category-btn wisata" id="wisataButton">
                🏞️ Wisata
            </button>

            <button type="button" class="category-btn kuliner" id="kulinerButton">
                🍜 Kuliner
            </button>

            <button type="button" class="category-btn hotel" id="hotelButton">
                🏨 Hotel
            </button>

            <button type="button" class="category-btn ibadah" id="ibadahButton">
                🕌 Tempat Ibadah
            </button>

        </div>


        <!-- SEARCH TEMPAT -->
        <div
            id="placeSearchPanel"
            style="
                display:none;
                margin-top:12px;
                width:100%;
            "
        >

            <input
                type="text"
                id="placeSearchInput"
                placeholder="🔎 Cari nama tempat (wisata, kuliner, hotel, ibadah)..."
                autocomplete="off"
                style="
                    width:100%;
                    box-sizing:border-box;
                    padding:10px 13px;
                    border:1px solid #d1d5db;
                    border-radius:10px;
                    outline:none;
                    font-size:13.5px;
                    margin-bottom:8px;
                    background:#fff;
                "
            >

            <div style="display:flex;gap:6px;margin-bottom:8px;">
                <div style="flex:1;">
                    <label for="placeRatingSort" style="display:block;font-size:11px;font-weight:700;color:#4b5563;margin-bottom:3px;">
                        ↕️ Urutkan:
                    </label>
                    <select
                        id="placeRatingSort"
                        aria-label="Urutkan tempat"
                        style="width:100%;box-sizing:border-box;padding:8px 8px;border:1px solid #d1d5db;border-radius:8px;font-size:12px;background:#fff;color:#1f2937;cursor:pointer;"
                    >
                        <option value="rating-desc">⭐ Rating Tertinggi (5 → 1)</option>
                        <option value="rating-asc">⭐ Rating Terendah (1 → 5)</option>
                        <option value="default">📍 Jarak Terdekat (Default)</option>
                        <option value="reviews-desc">💬 Ulasan Terbanyak</option>
                    </select>
                </div>

                <div style="flex:1;">
                    <label for="placeRatingFilter" style="display:block;font-size:11px;font-weight:700;color:#4b5563;margin-bottom:3px;">
                        🔍 Filter Rating:
                    </label>
                    <select
                        id="placeRatingFilter"
                        aria-label="Filter rating minimum"
                        style="width:100%;box-sizing:border-box;padding:8px 8px;border:1px solid #d1d5db;border-radius:8px;font-size:12px;background:#fff;color:#1f2937;cursor:pointer;"
                    >
                        <option value="all">Semua Rating</option>
                        <option value="4.5">⭐ 4.5 ke atas (Sangat Bagus)</option>
                        <option value="4.0">⭐ 4.0 ke atas (Bagus)</option>
                        <option value="3.5">⭐ 3.5 ke atas (Cukup)</option>
                        <option value="under-4">⭐ Di bawah 4.0</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;background:#f3f4f6;border:1px solid #e5e7eb;border-radius:8px;padding:6px 10px;margin-bottom:8px;font-size:11px;">
                <div id="placeRatingNotice" style="color:#374151;font-weight:600;display:flex;align-items:center;gap:5px;">
                    <svg viewBox="0 0 24 24" width="13" height="13" style="flex-shrink:0;"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                    <span>Google Maps Rating</span>
                </div>
                <div id="placeResultCount" style="color:#6b7280;font-weight:700;">0 tempat</div>
            </div>

            <div
                id="placeSearchResults"
                style="
                    max-height:290px;
                    overflow-y:auto;
                "
            ></div>

        </div>


        <div id="placesLoading">⏳ Mencari tempat...</div>


        <button type="button" id="clearPlaces">
            ✕ Hapus marker tempat
        </button>

    </div>


    <!-- LEAFLET -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


    <script>

        /* =====================================================
           DATA LARAVEL
        ====================================================== */

        const countries = @json($countries);

        const provinces = @json($provinces);

        const cities = @json($cities);

        /* =====================================================
           FAVORIT (disimpan di database, tampil di profil)
        ====================================================== */

        @php
            $favoriteKeys = auth()->check()
                ? \App\Models\FavoritePlace::where('user_id', auth()->id())->pluck('place_key')->values()
                : collect();
        @endphp

        const isLoggedIn = @json(auth()->check());
        const loginUrl = @json(route('login'));
        const profileUrl = @json(auth()->check() ? route('profile.show') . '#destinasi-favorit' : null);
        const favoriteToggleUrl = @json(route('favorite-places.toggle'));
        const favoriteKeys = new Set(@json($favoriteKeys));
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

        // Daftar resmi provinsi Indonesia yang boleh tampil di mode Indonesia.
        const indonesiaProvinceNames = new Set([
            'aceh', 'sumatera utara', 'sumatera barat', 'riau',
            'kepulauan riau', 'jambi', 'sumatera selatan',
            'kepulauan bangka belitung', 'bengkulu', 'lampung',
            'dki jakarta', 'daerah khusus jakarta', 'daerah khusus ibukota jakarta',
            'jakarta', 'jawa barat', 'banten', 'jawa tengah',
            'daerah istimewa yogyakarta', 'di yogyakarta', 'yogyakarta',
            'jawa timur', 'bali', 'nusa tenggara barat', 'nusa tenggara timur',
            'kalimantan barat', 'kalimantan tengah', 'kalimantan selatan',
            'kalimantan timur', 'kalimantan utara', 'sulawesi utara',
            'sulawesi tengah', 'sulawesi selatan', 'sulawesi tenggara',
            'gorontalo', 'sulawesi barat', 'maluku', 'maluku utara',
            'papua', 'papua barat', 'papua selatan', 'papua tengah',
            'papua pegunungan', 'papua barat daya'
        ]);

        function normalizeProvinceName(value) {
            return String(value || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, ' ')
                .trim();
        }

        const indonesiaCountry = countries.find(item =>
            String(item.code || '').toLowerCase() === 'id'
        ) || {
            id: null,
            name: 'Indonesia',
            code: 'id',
            latitude: -2.5489,
            longitude: 118.0149
        };

        /* =====================================================
           MAP
        ====================================================== */

        const map = L.map('map', {
            zoomControl: true,
            attributionControl: true,
            zoomAnimation: true,
            fadeAnimation: true,
            markerZoomAnimation: true,
            zoomSnap: 1,
            zoomDelta: 1,
            wheelPxPerZoomLevel: 80
        });

        const osm = L.tileLayer(
            'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                minZoom: 2,
                maxZoom: 19,
                maxNativeZoom: 19,
                detectRetina: true,
                updateWhenZooming: true,
                updateWhenIdle: true,
                keepBuffer: 2,
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
            }
        );

        osm.addTo(map);

        map.setView([-2.5, 118], 5);

        /* =====================================================
           SEARCH ELEMENT
        ====================================================== */

        const searchToggle = document.getElementById('searchToggle');
        const searchPanel = document.getElementById('searchPanel');
        const closeSearch = document.getElementById('closeSearch');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchTitle = document.getElementById('searchTitle');
        const searchMode = document.getElementById('searchMode');
        const backToProvince = document.getElementById('backToProvince');
        const searchInputWrapper = document.querySelector('.search-input-wrapper');

        /* LOCATION ELEMENT */
        const locationInfo = document.getElementById('locationInfo');
        const locationName = document.getElementById('locationName');
        const locationAddress = document.getElementById('locationAddress');
        const cityWikiContainer = document.getElementById('cityWikiContainer');
        const cityWikiLink = document.getElementById('cityWikiLink');
        const cityWikiLabel = document.getElementById('cityWikiLabel');

        /* INFO PROVINSI + CUACA ELEMENT */
        const provinceInfo = document.getElementById('provinceInfo');
        const provinceSummary = document.getElementById('provinceSummary');
        const provinceWeather = document.getElementById('provinceWeather');

        /* CATEGORY ELEMENT */
        const categoryOptions = document.getElementById('categoryOptions');
        const wisataButton = document.getElementById('wisataButton');
        const kulinerButton = document.getElementById('kulinerButton');
        const hotelButton = document.getElementById('hotelButton');
        const ibadahButton = document.getElementById('ibadahButton');
        const placesLoading = document.getElementById('placesLoading');
        const clearPlaces = document.getElementById('clearPlaces');
        const placeSearchPanel = document.getElementById('placeSearchPanel');
        const placeSearchInput = document.getElementById('placeSearchInput');
        const placeSearchResults = document.getElementById('placeSearchResults');

        const placeRatingSort = document.getElementById('placeRatingSort');
        const placeRatingNotice = document.getElementById('placeRatingNotice');
        const placeRatingFilter = document.getElementById('placeRatingFilter');
        const placeResultCount = document.getElementById('placeResultCount');

        /* ROUTE ELEMENT */
        const routePanel = document.getElementById('routePanel');
        const routeTitle = document.getElementById('routeTitle');
        const routeClose = document.getElementById('routeClose');
        const routeOriginSelect = document.getElementById('routeOrigin');
        const routeModesWrap = document.getElementById('routeModes');
        const routeSummary = document.getElementById('routeSummary');
        const routeSteps = document.getElementById('routeSteps');
        const routeGmaps = document.getElementById('routeGmaps');


        /* =====================================================
           STATE
        ====================================================== */

        let currentCountry = indonesiaCountry;
        let currentProvince = null;
        let currentCity = null;
        let currentCountryMarker = null;
        let currentProvinceMarker = null;
        let currentCityMarker = null;
        let placeMarkers = [];
        let selectedPlaceMarker = null;
        let loadedPlaces = [];
        let currentPlaceCategory = '';

        // Marker kota-kota provinsi (ditampilkan otomatis setelah provinsi dipilih)
        let provinceCityMarkers = [];

        // Versi untuk abort geocoding kalau user ganti provinsi
        let provinceVersion = 0;

        // State info provinsi + cuaca
        let provinceDetail = null;
        let weatherTimer = null;
        let provinceRequestId = 0;
        const WEATHER_REFRESH_MS = 5 * 60 * 1000;

        // State RUTE
        let routeDestination = null;      // { lat, lng, name, googleUrl }
        let routeMode = 'car';            // car | motor | bike | foot
        let routeOriginType = 'me';       // me | city
        let routeLayer = null;            // layer garis rute
        let routeOriginMarker = null;     // marker titik awal
        let routeRequestId = 0;           // untuk membatalkan request lama
        let userLocation = null;          // { lat, lng } hasil GPS

        /* =====================================================
           WARNA UNIK UNTUK SETIAP KOTA
           Golden-angle (137.5°) menghasilkan distribusi hue
           yang merata → setiap kota dapat warna berbeda.
        ====================================================== */

        function getCityColor(index) {
            const hue = (index * 137.508) % 360;
            return `hsl(${hue.toFixed(0)}, 72%, 45%)`;
        }

        /* =====================================================
           ICON PROVINSI
        ====================================================== */

        function createProvinceIcon() {
            return L.divIcon({
                className: '',
                html:
                    '<div style="' +
                    'width:34px;' +
                    'height:34px;' +
                    'border-radius:50% 50% 50% 0;' +
                    'background:#e53935;' +
                    'transform:rotate(-45deg);' +
                    'border:3px solid white;' +
                    'box-shadow:0 3px 10px rgba(0,0,0,.4);' +
                    '"></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34]
            });
        }

        /* ICON KOTA BERWARNA */
        function createColoredCityIcon(color) {
            return L.divIcon({
                className: '',
                html:
                    '<div style="' +
                    'width:30px;' +
                    'height:30px;' +
                    'border-radius:50% 50% 50% 0;' +
                    'background:' + color + ';' +
                    'transform:rotate(-45deg);' +
                    'border:3px solid white;' +
                    'box-shadow:0 3px 10px rgba(0,0,0,.45);' +
                    'position:relative;' +
                    '"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30]
            });
        }

        /* ICON KOTA (fallback) */
        function createCityIcon() {
            return L.divIcon({
                className: '',
                html:
                    '<div style="' +
                    'width:30px;' +
                    'height:30px;' +
                    'border-radius:50% 50% 50% 0;' +
                    'background:#1976d2;' +
                    'transform:rotate(-45deg);' +
                    'border:3px solid white;' +
                    'box-shadow:0 3px 10px rgba(0,0,0,.4);' +
                    '"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 30],
                popupAnchor: [0, -30]
            });
        }

        /* ICON WISATA */
        function createTourismIcon() {
            return L.divIcon({
                className: '',
                html: '<div class="tourism-marker"></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34]
            });
        }

        /* ICON KULINER */
        function createFoodIcon() {
            return L.divIcon({
                className: '',
                html: '<div class="food-marker"></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34]
            });
        }

        /* ICON HOTEL */
        function createHotelIcon() {
            return L.divIcon({
                className: '',
                html: '<div class="hotel-marker"></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34]
            });
        }

        /* ICON TEMPAT IBADAH */
        function createWorshipIcon() {
            return L.divIcon({
                className: '',
                html: '<div class="ibadah-marker"></div>',
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34]
            });
        }

        function createPlaceIcon(category) {
            switch (category) {
                case 'wisata':  return createTourismIcon();
                case 'kuliner': return createFoodIcon();
                case 'hotel':   return createHotelIcon();
                case 'ibadah':  return createWorshipIcon();
                default:        return createTourismIcon();
            }
        }

        function categoryEmoji(category) {
            const map = {
                wisata:  '🏞️',
                kuliner: '🍜',
                hotel:   '🏨',
                ibadah:  '🕌'
            };
            return map[category] || '📍';
        }

        function categoryLabel(category) {
            const map = {
                wisata:  '🏞️ Wisata',
                kuliner: '🍜 Kuliner',
                hotel:   '🏨 Hotel',
                ibadah:  '🕌 Tempat Ibadah'
            };
            return map[category] || '📍 Tempat';
        }

        function categorySearchLabel(category) {
            const map = {
                wisata:  'tempat wisata',
                kuliner: 'tempat kuliner',
                hotel:   'hotel',
                ibadah:  'tempat ibadah'
            };
            return map[category] || 'tempat';
        }


        /* =====================================================
           SEARCH OPEN / CLOSE
        ====================================================== */

        searchToggle.addEventListener('click', function () {
            searchPanel.style.display = 'block';
            searchToggle.style.display = 'none';
            if (!currentProvince) {
                searchInput.focus();
                renderProvinceResults();
            } else {
                showProvinceCitiesHint();
            }
        });

        closeSearch.addEventListener('click', function () {
            searchPanel.style.display = 'none';
            searchToggle.style.display = 'block';
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                searchPanel.style.display = 'none';
                searchToggle.style.display = 'block';
            }
        });


        /* =====================================================
           PROVINSI INDONESIA (LIST MODE)
        ====================================================== */

        function renderProvinceResults(keyword = '') {

            searchTitle.textContent = '🔍 Cari Provinsi Indonesia';
            searchMode.textContent = 'Indonesia';
            searchInput.placeholder = 'Cari provinsi Indonesia...';
            backToProvince.style.display = 'none';
            searchInputWrapper.style.display = '';
            searchInput.value = keyword;

            const search = String(keyword || '').toLowerCase().trim();

            const filtered = provinces
                .filter(province => {
                    const name = normalizeProvinceName(province.name);
                    return indonesiaProvinceNames.has(name);
                })
                .filter(province =>
                    normalizeProvinceName(province.name).includes(
                        normalizeProvinceName(search)
                    ) || String(province.name || '').toLowerCase().includes(search)
                );

            searchResults.innerHTML = '';

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Provinsi Indonesia tidak ditemukan.
                    </div>
                `;
                return;
            }

            filtered.forEach(province => {
                const item = document.createElement('div');
                item.className = 'search-result';

                item.innerHTML = `
                    <div class="result-name">
                        📍 ${escapeHtml(province.name)}
                    </div>
                    <div class="result-info">
                        Provinsi • Indonesia
                    </div>
                `;

                item.addEventListener('click', function () {
                    selectProvince(province);
                });

                searchResults.appendChild(item);
            });
        }


        /* =====================================================
           HINT MODE PROVINSI (kota ditampilkan di peta)
        ====================================================== */

        function showProvinceCitiesHint(extra) {

            if (!currentProvince) return;

            const count = cities.filter(c =>
                String(c.province_id) === String(currentProvince.id)
            ).length;

            searchTitle.textContent = '🗺️ ' + currentProvince.name;
            searchMode.textContent = currentProvince.name;
            backToProvince.style.display = 'block';
            backToProvince.textContent = '← Kembali ke Daftar Provinsi';
            searchInputWrapper.style.display = 'none';

            searchResults.innerHTML = `
                <div class="no-result" style="padding: 22px 18px; line-height: 1.7; text-align: left;">
                    📍 <strong>${count} kota/kabupaten</strong> di <strong>${escapeHtml(currentProvince.name)}</strong> ditampilkan sebagai marker di peta.
                    <div style="margin-top: 10px; font-size: 12px; color: #475569;">
                        👉 Klik salah satu <strong>marker berwarna</strong> di peta untuk memilih kota dan melihat tempat wisata, kuliner, hotel, atau tempat ibadah.
                    </div>
                    ${extra ? `<div style="margin-top: 10px; font-size: 12px; color: #64748b; font-style: italic;">${escapeHtml(extra)}</div>` : ''}
                </div>
            `;
        }


        /* =====================================================
           PILIH PROVINSI
        ====================================================== */

        async function selectProvince(province) {

            provinceVersion++;
            const myVersion = provinceVersion;

            currentProvince = {
                ...province,
                latitude: null,
                longitude: null
            };
            currentCity = null;

            clearPlaceMarkers();
            clearProvinceCityMarkers();
            resetProvinceDetail();

            if (currentCountryMarker) {
                map.removeLayer(currentCountryMarker);
                currentCountryMarker = null;
            }

            if (currentProvinceMarker) {
                map.removeLayer(currentProvinceMarker);
                currentProvinceMarker = null;
            }

            if (currentCityMarker) {
                map.removeLayer(currentCityMarker);
                currentCityMarker = null;
            }

            categoryOptions.style.display = 'none';
            placesLoading.style.display = 'none';
            clearPlaces.style.display = 'none';
            locationInfo.style.display = 'none';

            searchInput.value = '';

            // Resolve koordinat provinsi
            await searchProvinceOpenStreetMap(currentProvince);

            if (myVersion !== provinceVersion) return;

            // Update panel search ke mode provinsi
            showProvinceCitiesHint('Memuat marker kota...');

            // Tampilkan semua marker kota
            await showProvinceCitiesOnMap(myVersion);
        }


        /* =====================================================
           CARI PROVINSI OSM
        ====================================================== */

        async function searchProvinceOpenStreetMap(province) {

            try {
                const url =
                    '/api/geocode?' +
                    new URLSearchParams({
                        name: province.name,
                        type: 'province',
                        country_code: currentCountry ? currentCountry.code : 'id',
                        country_name: currentCountry ? currentCountry.name : 'Indonesia'
                    });

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();
                console.log('Geocoding provinsi:', data);

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Provinsi tidak ditemukan.');
                }

                showProvinceOnMap(
                    parseFloat(data.latitude),
                    parseFloat(data.longitude),
                    province.name,
                    data.display_name
                );

            } catch (error) {
                console.error('Geocoding provinsi:', error);
                locationInfo.style.display = 'block';
                locationName.textContent = province.name;
                locationAddress.textContent =
                    'Koordinat provinsi sedang tidak tersedia. Coba pilih lagi.';
            }
        }


        /* =====================================================
           CUACA REAL TIME (Open-Meteo)
        ====================================================== */

        function weatherCodeInfo(code, isDay) {
            const day = isDay === 1 || isDay === true;
            const map = {
                0:  [day ? 'Cerah' : 'Cerah (malam)', day ? '☀️' : '🌙'],
                1:  ['Cerah berawan', day ? '🌤️' : '🌙'],
                2:  ['Berawan sebagian', '⛅'],
                3:  ['Berawan tebal', '☁️'],
                45: ['Berkabut', '🌫️'],
                48: ['Kabut es', '🌫️'],
                51: ['Gerimis ringan', '🌦️'],
                53: ['Gerimis sedang', '🌦️'],
                55: ['Gerimis lebat', '🌧️'],
                56: ['Gerimis beku ringan', '🌧️'],
                57: ['Gerimis beku lebat', '🌧️'],
                61: ['Hujan ringan', '🌦️'],
                63: ['Hujan sedang', '🌧️'],
                65: ['Hujan lebat', '🌧️'],
                66: ['Hujan beku ringan', '🌧️'],
                67: ['Hujan beku lebat', '🌧️'],
                71: ['Salju ringan', '🌨️'],
                73: ['Salju sedang', '🌨️'],
                75: ['Salju lebat', '❄️'],
                77: ['Butiran salju', '❄️'],
                80: ['Hujan lokal ringan', '🌦️'],
                81: ['Hujan lokal sedang', '🌧️'],
                82: ['Hujan lokal deras', '⛈️'],
                85: ['Hujan salju ringan', '🌨️'],
                86: ['Hujan salju lebat', '🌨️'],
                95: ['Badai petir', '⛈️'],
                96: ['Badai petir + es ringan', '⛈️'],
                99: ['Badai petir + es lebat', '⛈️']
            };
            const found = map[Number(code)];
            return found
                ? { text: found[0], icon: found[1] }
                : { text: 'Tidak diketahui', icon: '🌡️' };
        }

        function windDirectionText(degree) {
            const deg = Number(degree);
            if (!Number.isFinite(deg)) return '';
            const names = ['Utara', 'Timur Laut', 'Timur', 'Tenggara', 'Selatan', 'Barat Daya', 'Barat', 'Barat Laut'];
            return names[Math.round(deg / 45) % 8];
        }

        async function fetchWeather(latitude, longitude) {
            const url =
                'https://api.open-meteo.com/v1/forecast?' +
                new URLSearchParams({
                    latitude: latitude,
                    longitude: longitude,
                    current: [
                        'temperature_2m',
                        'relative_humidity_2m',
                        'apparent_temperature',
                        'is_day',
                        'precipitation',
                        'weather_code',
                        'cloud_cover',
                        'surface_pressure',
                        'wind_speed_10m',
                        'wind_direction_10m'
                    ].join(','),
                    daily: 'temperature_2m_max,temperature_2m_min,precipitation_probability_max',
                    timezone: 'auto',
                    forecast_days: 1
                });

            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) {
                throw new Error('Layanan cuaca tidak merespons (' + response.status + ').');
            }

            const data = await response.json();
            if (!data || !data.current) {
                throw new Error('Data cuaca tidak tersedia.');
            }
            return data;
        }

        function renderWeatherHtml(weather) {
            const c = weather.current;
            const d = weather.daily || {};
            const info = weatherCodeInfo(c.weather_code, c.is_day);

            const num = function (value, digits) {
                const n = Number(value);
                return Number.isFinite(n) ? n.toFixed(digits) : '-';
            };

            const tMax = d.temperature_2m_max ? num(d.temperature_2m_max[0], 0) : '-';
            const tMin = d.temperature_2m_min ? num(d.temperature_2m_min[0], 0) : '-';
            const rainChance = d.precipitation_probability_max
                ? d.precipitation_probability_max[0]
                : null;

            const timeText = String(c.time || '').split('T')[1] || '';
            const zone = weather.timezone_abbreviation || '';
            const wind = windDirectionText(c.wind_direction_10m);

            return `
                <div class="weather-card ${c.is_day === 0 ? 'night' : ''}">
                    <div class="weather-main">
                        <div>
                            <div class="weather-temp">${num(c.temperature_2m, 1)}°C</div>
                            <div class="weather-desc">${escapeHtml(info.text)}</div>
                            <div class="weather-minmax">
                                Terasa ${num(c.apparent_temperature, 1)}°C • Maks ${tMax}° / Min ${tMin}°
                            </div>
                        </div>
                        <div class="weather-icon">${info.icon}</div>
                    </div>

                    <div class="weather-grid">
                        <div class="weather-item">
                            <div class="w-label">💧 Kelembapan</div>
                            <div class="w-value">${num(c.relative_humidity_2m, 0)}%</div>
                        </div>
                        <div class="weather-item">
                            <div class="w-label">💨 Angin</div>
                            <div class="w-value">${num(c.wind_speed_10m, 1)} km/j</div>
                        </div>
                        <div class="weather-item">
                            <div class="w-label">🧭 Arah</div>
                            <div class="w-value">${escapeHtml(wind || '-')}</div>
                        </div>
                        <div class="weather-item">
                            <div class="w-label">☁️ Awan</div>
                            <div class="w-value">${num(c.cloud_cover, 0)}%</div>
                        </div>
                        <div class="weather-item">
                            <div class="w-label">🌧️ Curah hujan</div>
                            <div class="w-value">${num(c.precipitation, 1)} mm</div>
                        </div>
                        <div class="weather-item">
                            <div class="w-label">☔ Peluang hujan</div>
                            <div class="w-value">${rainChance !== null ? num(rainChance, 0) + '%' : '-'}</div>
                        </div>
                    </div>

                    <div class="weather-footer">
                        <span>
                            <span class="weather-live-dot"></span>Real time • ${escapeHtml(timeText)} ${escapeHtml(zone)}
                            • Tekanan ${num(c.surface_pressure, 0)} hPa
                        </span>
                        <button type="button" class="weather-refresh-btn" data-weather-refresh="1">↻ Segarkan</button>
                    </div>
                </div>
            `;
        }


        /* =====================================================
           INFO PROVINSI (Wikipedia bahasa Indonesia)
        ====================================================== */

        const provinceWikiTitleOverrides = {
            'dki jakarta': 'Daerah_Khusus_Ibukota_Jakarta',
            'daerah khusus jakarta': 'Daerah_Khusus_Ibukota_Jakarta',
            'daerah khusus ibukota jakarta': 'Daerah_Khusus_Ibukota_Jakarta',
            'jakarta': 'Daerah_Khusus_Ibukota_Jakarta',
            'di yogyakarta': 'Daerah_Istimewa_Yogyakarta',
            'yogyakarta': 'Daerah_Istimewa_Yogyakarta',
            'daerah istimewa yogyakarta': 'Daerah_Istimewa_Yogyakarta',
            'bangka belitung': 'Kepulauan_Bangka_Belitung',
            'kepulauan bangka belitung': 'Kepulauan_Bangka_Belitung',
            'ntb': 'Nusa_Tenggara_Barat',
            'ntt': 'Nusa_Tenggara_Timur'
        };

        function getProvinceWikiTitle(name) {
            const key = normalizeProvinceName(name);
            if (provinceWikiTitleOverrides[key]) {
                return provinceWikiTitleOverrides[key];
            }
            return String(name || '').trim().replace(/\s+/g, '_');
        }

        async function fetchProvinceSummary(name) {
            const title = getProvinceWikiTitle(name);
            const fallbackUrl = 'https://id.wikipedia.org/wiki/' + encodeURIComponent(title);

            const response = await fetch(
                'https://id.wikipedia.org/api/rest_v1/page/summary/' + encodeURIComponent(title),
                { headers: { 'Accept': 'application/json' } }
            );

            if (!response.ok) {
                const error = new Error('Ringkasan provinsi tidak ditemukan.');
                error.wikiUrl = fallbackUrl;
                throw error;
            }

            const data = await response.json();

            let extract = String(data.extract || '').trim();
            if (extract.length > 420) {
                extract = extract.slice(0, 420).replace(/\s+\S*$/, '') + '…';
            }

            return {
                extract: extract,
                description: data.description || '',
                thumbnail: data.thumbnail ? data.thumbnail.source : null,
                url: (data.content_urls && data.content_urls.desktop && data.content_urls.desktop.page) || fallbackUrl
            };
        }


        /* =====================================================
           RENDER INFO PROVINSI
        ====================================================== */

        function renderSummaryHtml(detail, compact) {
            if (!detail) return '';

            const wikiLink = detail.wikiUrl
                ? `<a class="prov-wiki-link" href="${escapeHtml(detail.wikiUrl)}" target="_blank" rel="noopener noreferrer">
                        🌐 Selengkapnya di Wikipedia &nearr;
                   </a>`
                : '';

            if (detail.summaryLoading) {
                return '<div class="weather-loading">⏳ Memuat informasi provinsi...</div>';
            }

            if (!detail.summary) {
                return `
                    <div class="prov-summary-text" style="margin-bottom:12px;">
                        Ringkasan provinsi belum bisa dimuat.
                        <div>${wikiLink}</div>
                    </div>
                `;
            }

            const s = detail.summary;

            return `
                <div class="prov-summary">
                    ${!compact && s.thumbnail
                        ? `<img src="${escapeHtml(s.thumbnail)}" alt="${escapeHtml(detail.name)}" loading="lazy">`
                        : ''}
                    <div class="prov-summary-text">
                        ${s.description ? `<div style="font-weight:700;color:#0f172a;margin-bottom:3px;">${escapeHtml(s.description)}</div>` : ''}
                        ${escapeHtml(compact && s.extract.length > 200 ? s.extract.slice(0, 200).replace(/\s+\S*$/, '') + '…' : s.extract)}
                        <div>${wikiLink}</div>
                    </div>
                </div>
            `;
        }

        function renderWeatherSectionHtml(detail) {
            if (!detail) return '';

            if (detail.weatherLoading) {
                return '<div class="weather-loading">⏳ Mengambil cuaca real time...</div>';
            }

            if (detail.weatherError) {
                return `
                    <div class="weather-error">
                        ❌ ${escapeHtml(detail.weatherError)}
                        <div style="margin-top:6px;">
                            <button type="button" class="weather-refresh-btn" style="background:#b91c1c;" data-weather-refresh="1">↻ Coba lagi</button>
                        </div>
                    </div>
                `;
            }

            return detail.weather ? renderWeatherHtml(detail.weather) : '';
        }

        function refreshProvinceViews() {
            if (!provinceDetail) return;
            provinceInfo.style.display = 'block';
            provinceSummary.innerHTML = renderSummaryHtml(provinceDetail, false);
            provinceWeather.innerHTML = renderWeatherSectionHtml(provinceDetail);
        }

        function hideProvinceInfoPanel() {
            provinceInfo.style.display = 'none';
            provinceSummary.innerHTML = '';
            provinceWeather.innerHTML = '';
        }

        function stopWeatherRefresh() {
            if (weatherTimer) {
                clearInterval(weatherTimer);
                weatherTimer = null;
            }
        }

        function resetProvinceDetail() {
            provinceRequestId++;
            stopWeatherRefresh();
            provinceDetail = null;
            hideProvinceInfoPanel();
        }

        async function loadProvinceWeather(requestId) {
            if (!provinceDetail || requestId !== provinceRequestId) return;

            try {
                const weather = await fetchWeather(provinceDetail.latitude, provinceDetail.longitude);
                if (requestId !== provinceRequestId || !provinceDetail) return;
                provinceDetail.weather = weather;
                provinceDetail.weatherError = null;
            } catch (error) {
                console.error('Cuaca provinsi:', error);
                if (requestId !== provinceRequestId || !provinceDetail) return;
                if (!provinceDetail.weather) {
                    provinceDetail.weatherError =
                        error.message || 'Cuaca tidak dapat dimuat saat ini.';
                }
            } finally {
                if (requestId === provinceRequestId && provinceDetail) {
                    provinceDetail.weatherLoading = false;
                    refreshProvinceViews();
                }
            }
        }

        async function loadProvinceSummary(requestId) {
            if (!provinceDetail || requestId !== provinceRequestId) return;

            try {
                const summary = await fetchProvinceSummary(provinceDetail.name);
                if (requestId !== provinceRequestId || !provinceDetail) return;
                provinceDetail.summary = summary;
                provinceDetail.wikiUrl = summary.url;
            } catch (error) {
                console.error('Ringkasan provinsi:', error);
                if (requestId !== provinceRequestId || !provinceDetail) return;
                provinceDetail.summary = null;
                provinceDetail.wikiUrl = error.wikiUrl || provinceDetail.wikiUrl;
            } finally {
                if (requestId === provinceRequestId && provinceDetail) {
                    provinceDetail.summaryLoading = false;
                    refreshProvinceViews();
                }
            }
        }

        function startProvinceDetail(name, latitude, longitude, address) {
            provinceRequestId++;
            stopWeatherRefresh();

            const requestId = provinceRequestId;

            provinceDetail = {
                name: name,
                latitude: latitude,
                longitude: longitude,
                address: address || '',
                wikiUrl: 'https://id.wikipedia.org/wiki/' + encodeURIComponent(getProvinceWikiTitle(name)),
                summary: null,
                summaryLoading: true,
                weather: null,
                weatherLoading: true,
                weatherError: null
            };

            refreshProvinceViews();

            loadProvinceSummary(requestId);
            loadProvinceWeather(requestId);

            weatherTimer = setInterval(function () {
                loadProvinceWeather(requestId);
            }, WEATHER_REFRESH_MS);
        }

        document.addEventListener('click', function (event) {
            const btn = event.target.closest('[data-weather-refresh]');
            if (!btn || !provinceDetail) return;

            event.preventDefault();
            event.stopPropagation();

            provinceDetail.weatherLoading = !provinceDetail.weather;
            provinceDetail.weatherError = null;
            refreshProvinceViews();

            loadProvinceWeather(provinceRequestId);
        });


        /* =====================================================
           TAMPILKAN PROVINSI DI PETA
        ====================================================== */

        function showProvinceOnMap(latitude, longitude, name, address) {
            latitude = Number(latitude);
            longitude = Number(longitude);

            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                throw new Error('Koordinat provinsi tidak valid.');
            }

            currentProvince.latitude = latitude;
            currentProvince.longitude = longitude;

            currentProvinceMarker =
                L.marker(
                    [latitude, longitude],
                    { icon: createProvinceIcon() }
                ).addTo(map);

            currentProvinceMarker.bindPopup(
                '<div class="place-popup-title">' + escapeHtml(name) + '</div>' +
                '<div class="place-popup-type">Provinsi • ' +
                escapeHtml(currentCountry ? currentCountry.name : 'Indonesia') + '</div>'
            );

            map.flyTo([latitude, longitude], 8, { animate: true, duration: 1.5 });

            locationInfo.style.display = 'block';
            locationName.textContent = name;
            locationAddress.textContent = address;

            startProvinceDetail(name, latitude, longitude, address);
        }


        /* =====================================================
           MARKER SEMUA KOTA DARI PROVINSI
        ====================================================== */

        function clearProvinceCityMarkers() {
            provinceCityMarkers.forEach(m => map.removeLayer(m));
            provinceCityMarkers = [];
        }

        function addCityMarkerForProvince(city, latitude, longitude, color) {
            const marker = L.marker([latitude, longitude], {
                icon: createColoredCityIcon(color)
            });

            marker.bindPopup(`
                <div class="place-popup-title" style="font-size:14px;font-weight:700;margin-bottom:2px;">
                    ${escapeHtml(city.name)}
                </div>
                <div class="place-popup-type" style="font-size:11.5px;color:#64748b;">
                    ${escapeHtml(city.type || 'Kota/Kabupaten')} • ${escapeHtml(currentProvince.name)}
                </div>
                <div style="margin-top:8px;">
                    <button
                        type="button"
                        class="city-select-btn"
                        style="
                            background:#1976d2;
                            color:white;
                            border:none;
                            padding:7px 14px;
                            border-radius:7px;
                            font-size:12px;
                            font-weight:700;
                            cursor:pointer;
                            display:inline-flex;
                            align-items:center;
                            gap:5px;
                        "
                    >
                        Pilih Kota Ini →
                    </button>
                </div>
            `);

            marker.on('popupopen', function () {
                const popupEl = marker.getPopup().getElement();
                if (!popupEl) return;
                const btn = popupEl.querySelector('.city-select-btn');
                if (btn) {
                    btn.onclick = function (ev) {
                        ev.stopPropagation();
                        selectCity(city, latitude, longitude);
                    };
                }
            });

            marker.addTo(map);
            provinceCityMarkers.push(marker);
            return marker;
        }

        function fitProvinceCityBounds() {
            if (provinceCityMarkers.length === 0) return;
            if (provinceCityMarkers.length === 1) {
                map.flyTo(provinceCityMarkers[0].getLatLng(), 11, {
                    animate: true, duration: 1.2
                });
                return;
            }
            const group = L.featureGroup(provinceCityMarkers);
            map.fitBounds(group.getBounds().pad(0.15), {
                animate: true, duration: 1.2, maxZoom: 11
            });
        }

        async function showProvinceCitiesOnMap(version) {

            clearProvinceCityMarkers();

            if (!currentProvince) return;

            const provinceCities = cities.filter(c =>
                String(c.province_id) === String(currentProvince.id)
            );

            if (provinceCities.length === 0) {
                showProvinceCitiesHint('Tidak ada data kota untuk provinsi ini.');
                return;
            }

            // Geocode setiap kota secara berurutan (backend sudah cache
            // hasil regencies per provinsi via emsifa, jadi cepat).
            let done = 0;
            for (let i = 0; i < provinceCities.length; i++) {

                if (version !== provinceVersion) return;

                const city = provinceCities[i];
                const color = getCityColor(i);

                try {
                    const url =
                        '/api/geocode?' +
                        new URLSearchParams({
                            name: city.name,
                            type: 'city',
                            province: currentProvince.name,
                            city_type: city.type || ''
                        });

                    const resp = await fetch(url, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await resp.json();

                    if (version !== provinceVersion) return;

                    if (data.success) {
                        const la = parseFloat(data.latitude);
                        const ln = parseFloat(data.longitude);

                        if (Number.isFinite(la) && Number.isFinite(ln)) {
                            addCityMarkerForProvince(city, la, ln, color);
                            done++;

                            // Update hint progress
                            if (done === 1 || (i + 1) % 5 === 0) {
                                showProvinceCitiesHint(
                                    `Memuat ${done}/${provinceCities.length} kota...`
                                );
                            }
                        }
                    }
                } catch (e) {
                    console.error('Geocode kota gagal:', city.name, e);
                }
            }

            if (version !== provinceVersion) return;

            // Fit bounds ke semua marker
            fitProvinceCityBounds();

            // Update hint final
            showProvinceCitiesHint();
        }


        /* =====================================================
           PILIH KOTA (dari marker)
        ====================================================== */

        async function selectCity(city, knownLat, knownLng) {

            let latitude = Number(knownLat);
            let longitude = Number(knownLng);
            const hasCoords = Number.isFinite(latitude) && Number.isFinite(longitude);

            currentCity = {
                ...city,
                latitude: hasCoords ? latitude : null,
                longitude: hasCoords ? longitude : null
            };

            clearPlaceMarkers();

            // Sembunyikan info provinsi (tapi JANGAN di-reset — agar
            // bisa ditampilkan lagi saat user kembali ke mode provinsi)
            hideProvinceInfoPanel();

            if (currentCityMarker) {
                map.removeLayer(currentCityMarker);
                currentCityMarker = null;
            }

            categoryOptions.style.display = 'none';
            placeSearchPanel.style.display = 'none';
            placeSearchInput.value = '';
            placeSearchResults.innerHTML = '';
            loadedPlaces = [];
            currentPlaceCategory = '';
            placesLoading.style.display = 'none';
            clearPlaces.style.display = 'none';
            locationInfo.style.display = 'none';

            // Tombol back → "kembali ke peta kota"
            backToProvince.style.display = 'block';
            backToProvince.textContent = '← Kembali ke Peta Kota';

            if (hasCoords) {
                // Fly ke kota
                map.flyTo([latitude, longitude], 13, {
                    animate: true, duration: 1.6
                });

                // Info panel
                locationInfo.style.display = 'block';
                locationName.textContent = city.name;
                locationAddress.textContent =
                    `${city.type || 'Kota/Kabupaten'} • ${currentProvince.name}, Indonesia`;

                // Kategori muncul
                categoryOptions.style.display = 'flex';

                // Buka popup marker yang sesuai
                for (const m of provinceCityMarkers) {
                    const pos = m.getLatLng();
                    if (Math.abs(pos.lat - latitude) < 0.0005 &&
                        Math.abs(pos.lng - longitude) < 0.0005) {
                        m.openPopup();
                        break;
                    }
                }
            } else {
                // Fallback: geocode via backend
                await searchCityOpenStreetMap(currentCity);
            }
        }

        async function searchCityOpenStreetMap(city) {
            try {
                const url =
                    '/api/geocode?' +
                    new URLSearchParams({
                        name: city.name,
                        type: 'city',
                        province: currentProvince.name,
                        city_type: city.type || ''
                    });

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();
                console.log('Geocoding kota/kabupaten:', data);

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Kota/kabupaten tidak ditemukan.');
                }

                const latitude = Number(data.latitude);
                const longitude = Number(data.longitude);

                if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                    throw new Error('Koordinat kota tidak valid.');
                }

                currentCity.latitude = latitude;
                currentCity.longitude = longitude;

                showCityOnMap(
                    latitude,
                    longitude,
                    city.name,
                    `${city.type || 'Kota/Kabupaten'} • ${currentProvince.name}, Indonesia`
                );

            } catch (error) {
                console.error('Geocoding kota/kabupaten:', error);

                locationInfo.style.display = 'block';
                locationName.textContent = city.name;
                locationAddress.textContent =
                    'Koordinat kota tidak dapat ditentukan. Silakan pilih kota kembali.';
            }
        }

        /* =====================================================
           TAMPILKAN KOTA (fallback path)
        ====================================================== */

        function showCityOnMap(latitude, longitude, name, address) {
            latitude = Number(latitude);
            longitude = Number(longitude);

            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                throw new Error('Koordinat kota tidak valid.');
            }

            currentCity.latitude = latitude;
            currentCity.longitude = longitude;

            currentCityMarker =
                L.marker(
                    [latitude, longitude],
                    { icon: createCityIcon() }
                ).addTo(map);

            currentCityMarker.bindPopup(`
                <div class="place-popup-title">${escapeHtml(name)}</div>
                <div class="place-popup-type">${escapeHtml(address)}</div>
            `);

            currentCityMarker.openPopup();

            map.flyTo([latitude, longitude], 13, {
                animate: true, duration: 1.6
            });

            locationInfo.style.display = 'block';
            locationName.textContent = name;
            locationAddress.textContent = address;

            categoryOptions.style.display = 'flex';
        }


        /* =====================================================
           INPUT SEARCH (hanya mode provinsi list)
        ====================================================== */

        searchInput.addEventListener('input', function () {
            // Di mode provinsi (dengan marker kota), input search disembunyikan
            if (currentProvince) return;
            renderProvinceResults(this.value);
        });


        /* =====================================================
           TOMBOL KEMBALI (berjenjang)
        ====================================================== */

        backToProvince.addEventListener('click', function () {

            // Jika sedang di mode KOTA → kembali ke mode PROVINSI (peta kota)
            if (currentCity) {

                currentCity = null;
                clearPlaceMarkers();

                if (currentCityMarker) {
                    map.removeLayer(currentCityMarker);
                    currentCityMarker = null;
                }

                categoryOptions.style.display = 'none';
                placeSearchPanel.style.display = 'none';
                placeSearchInput.value = '';
                placeSearchResults.innerHTML = '';
                loadedPlaces = [];
                currentPlaceCategory = '';
                placesLoading.style.display = 'none';
                clearPlaces.style.display = 'none';

                // Tampilkan lagi info provinsi + cuaca
                if (provinceDetail && currentProvince) {
                    locationInfo.style.display = 'block';
                    locationName.textContent = provinceDetail.name || currentProvince.name;
                    locationAddress.textContent =
                        provinceDetail.address ||
                        `${currentProvince.name}, Indonesia`;
                    refreshProvinceViews();
                }

                // Fit ke semua marker kota
                fitProvinceCityBounds();

                // Update back button
                backToProvince.textContent = '← Kembali ke Daftar Provinsi';

                // Update hint
                showProvinceCitiesHint();

                return;
            }

            // Jika sedang di mode PROVINSI → kembali ke DAFTAR PROVINSI
            if (currentProvince) {

                provinceVersion++;      // abort geocoding yang masih jalan
                resetProvinceDetail();
                clearProvinceCityMarkers();

                if (currentProvinceMarker) {
                    map.removeLayer(currentProvinceMarker);
                    currentProvinceMarker = null;
                }

                if (currentCityMarker) {
                    map.removeLayer(currentCityMarker);
                    currentCityMarker = null;
                }

                currentProvince = null;
                currentCity = null;

                clearPlaceMarkers();

                categoryOptions.style.display = 'none';
                placeSearchPanel.style.display = 'none';
                placeSearchInput.value = '';
                placeSearchResults.innerHTML = '';
                loadedPlaces = [];
                currentPlaceCategory = '';
                placesLoading.style.display = 'none';
                clearPlaces.style.display = 'none';
                locationInfo.style.display = 'none';

                searchInput.value = '';

                renderProvinceResults('');

                map.flyTo(
                    [parseFloat(indonesiaCountry.latitude), parseFloat(indonesiaCountry.longitude)],
                    5,
                    { animate: true, duration: 1.5 }
                );
                return;
            }
        });


        /* =====================================================
           KATEGORI: WISATA / KULINER / HOTEL / IBADAH
        ====================================================== */

        wisataButton.addEventListener('click', function () {
            if (!currentCity) return;
            loadPlaces('wisata');
        });

        kulinerButton.addEventListener('click', function () {
            if (!currentCity) return;
            loadPlaces('kuliner');
        });

        hotelButton.addEventListener('click', function () {
            if (!currentCity) return;
            loadPlaces('hotel');
        });

        ibadahButton.addEventListener('click', function () {
            if (!currentCity) return;
            loadPlaces('ibadah');
        });


        /* =====================================================
           LOAD PLACES
        ====================================================== */

        async function loadPlaces(category) {

            if (!currentCity) return;

            currentPlaceCategory = category;

            let latitude = currentCity.latitude;
            let longitude = currentCity.longitude;

            if (!latitude || !longitude) {
                if (currentCityMarker) {
                    const position = currentCityMarker.getLatLng();
                    latitude = position.lat;
                    longitude = position.lng;
                }
            }

            if (!latitude || !longitude) {
                alert('Koordinat kota belum tersedia.');
                return;
            }

            clearPlaceMarkers();

            loadedPlaces = [];
            placeSearchInput.value = '';
            placeSearchResults.innerHTML = '';

            placeSearchPanel.style.display = 'block';
            placesLoading.style.display = 'block';
            clearPlaces.style.display = 'none';

            try {
                const url =
                    `/api/places?` +
                    new URLSearchParams({
                        latitude: latitude,
                        longitude: longitude,
                        category: category
                    });

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Gagal mengambil data tempat.');
                }

                loadedPlaces = data.places || [];
                if (placeRatingFilter) {
                    placeRatingFilter.value = 'all';
                }
                if (placeRatingNotice) {
                    placeRatingNotice.innerHTML = `
                        <svg viewBox="0 0 24 24" width="13" height="13" style="flex-shrink:0;"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                        <span>Google Maps Rating (${loadedPlaces.length} data)</span>
                    `;
                }

                applyFiltersAndSort();

            } catch (error) {
                console.error(error);

                placeSearchResults.innerHTML = `
                    <div style="padding:12px;color:#c62828;background:#ffebee;border-radius:8px;font-size:13px;">
                        ❌ ${escapeHtml(error.message || 'Gagal mengambil data tempat.')}
                    </div>
                `;

            } finally {
                placesLoading.style.display = 'none';
            }
        }


        /* =====================================================
           RENDER HASIL SEARCH TEMPAT
        ====================================================== */

        function renderPlaceSearchResults(places, category) {

            if (!places || places.length === 0) {
                placeSearchResults.innerHTML = `
                    <div style="padding:14px;color:#64748b;background:#f8fafc;border:1px dashed #cbd5e1;border-radius:9px;font-size:12.5px;text-align:center;">
                        Tidak ada ${categorySearchLabel(category)} yang sesuai dengan filter atau pencarian.
                    </div>
                `;
                return;
            }

            const emoji = categoryEmoji(category);

            placeSearchResults.innerHTML =
                places.map((place, index) => {

                    const distance = place.distance !== undefined ? `${place.distance} km` : '';
                    const rating = Number(place.rating || 0);
                    const reviewCount = Number(place.user_rating_count || 0);
                    const fullStars = Math.min(5, Math.max(0, Math.round(rating)));
                    const starsHtml = '★'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
                    const googleMapsUrl = place.google_maps_url ||
                        `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.name + ' ' + (place.address || ''))}`;

                    return `
                        <div
                            class="place-search-item"
                            data-index="${index}"
                            style="padding:11px 12px;margin-bottom:7px;background:white;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer;transition:all .2s ease;box-shadow: 0 1px 3px rgba(0,0,0,0.03);"
                        >
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                                <div style="font-weight:700;font-size:13.5px;color:#1e293b;line-height:1.35;">
                                    ${emoji}
                                    ${escapeHtml(place.name)}
                                </div>
                                ${distance ? `
                                    <span style="flex-shrink:0;background:#f1f5f9;color:#475569;font-size:11px;font-weight:600;padding:2px 6px;border-radius:6px;">
                                        ${distance}
                                    </span>
                                ` : ''}
                            </div>

                            <div style="margin-top:3px;font-size:11.5px;color:#64748b;">
                                ${escapeHtml(place.type || '')}
                            </div>

                            <div style="margin-top:7px;display:flex;align-items:center;justify-content:space-between;gap:6px;flex-wrap:wrap;">
                                <div style="display:flex;align-items:center;gap:4px;">
                                    <span style="display:inline-flex;align-items:center;gap:2px;background:#fef3c7;color:#92400e;padding:1px 6px;border-radius:5px;font-weight:700;font-size:11.5px;">
                                        ⭐ ${rating.toFixed(1)}
                                    </span>
                                    <span style="color:#d97706;font-size:11px;letter-spacing:0.5px;">
                                        ${starsHtml}
                                    </span>
                                    <span style="font-size:11px;color:#64748b;">
                                        (${reviewCount.toLocaleString('id-ID')})
                                    </span>
                                </div>

                                <a
                                    href="${escapeHtml(googleMapsUrl)}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="place-google-btn"
                                    style="font-size:11px;color:#2563eb;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:3px;padding:3px 7px;border-radius:5px;background:#eff6ff;"
                                    title="Lihat ulasan dan foto di Google Maps"
                                >
                                    Google ↗
                                </a>
                            </div>
                        </div>
                    `;
                }).join('');

            const items = placeSearchResults.querySelectorAll('.place-search-item');

            items.forEach(item => {
                item.addEventListener('mouseenter', function () {
                    this.style.background = '#f8fafc';
                    this.style.borderColor = '#cbd5e1';
                });
                item.addEventListener('mouseleave', function () {
                    this.style.background = 'white';
                    this.style.borderColor = '#e2e8f0';
                });
                item.addEventListener('click', function () {
                    const index = parseInt(this.dataset.index, 10);
                    const place = places[index];
                    if (place) {
                        showSelectedPlace(place, category);
                    }
                });
            });

            const googleButtons = placeSearchResults.querySelectorAll('.place-google-btn');
            googleButtons.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });
        }


        /* =====================================================
           FILTER & SORT TEMPAT
        ====================================================== */

        function applyFiltersAndSort() {
            if (!loadedPlaces || loadedPlaces.length === 0) {
                renderPlaceSearchResults([], currentPlaceCategory);
                if (placeResultCount) placeResultCount.textContent = '0 tempat';
                return;
            }

            const keyword = placeSearchInput ? placeSearchInput.value.trim().toLowerCase() : '';
            const sortMode = placeRatingSort ? placeRatingSort.value : 'rating-desc';
            const filterRating = placeRatingFilter ? placeRatingFilter.value : 'all';

            let filtered = loadedPlaces.filter(place => {
                if (!keyword) return true;
                const name = (place.name || '').toLowerCase();
                const type = (place.type || '').toLowerCase();
                const address = (place.address || '').toLowerCase();
                return name.includes(keyword) || type.includes(keyword) || address.includes(keyword);
            });

            if (filterRating !== 'all') {
                if (filterRating === 'under-4') {
                    filtered = filtered.filter(place => {
                        const r = Number(place.rating);
                        return Number.isFinite(r) && r < 4.0;
                    });
                } else {
                    const min = parseFloat(filterRating);
                    if (Number.isFinite(min)) {
                        filtered = filtered.filter(place => {
                            const r = Number(place.rating);
                            return Number.isFinite(r) && r >= min;
                        });
                    }
                }
            }

            filtered.sort((a, b) => {
                const ar = (a.rating !== null && a.rating !== undefined && Number.isFinite(Number(a.rating))) ? Number(a.rating) : 0;
                const br = (b.rating !== null && b.rating !== undefined && Number.isFinite(Number(b.rating))) ? Number(b.rating) : 0;
                const ac = Number(a.user_rating_count) || 0;
                const bc = Number(b.user_rating_count) || 0;
                const ad = Number(a.distance) || 0;
                const bd = Number(b.distance) || 0;

                if (sortMode === 'rating-desc') {
                    if (br !== ar) return br - ar;
                    if (bc !== ac) return bc - ac;
                    return ad - bd;
                } else if (sortMode === 'rating-asc') {
                    if (ar !== br) return ar - br;
                    if (ac !== bc) return ac - bc;
                    return ad - bd;
                } else if (sortMode === 'reviews-desc') {
                    if (bc !== ac) return bc - ac;
                    return br - ar;
                } else {
                    if (ad !== bd) return ad - bd;
                    return br - ar;
                }
            });

            if (placeResultCount) {
                placeResultCount.textContent = `${filtered.length} tempat`;
            }

            renderPlaceSearchResults(filtered, currentPlaceCategory);
        }

        if (placeRatingSort) placeRatingSort.addEventListener('change', applyFiltersAndSort);
        if (placeRatingFilter) placeRatingFilter.addEventListener('change', applyFiltersAndSort);
        if (placeSearchInput) placeSearchInput.addEventListener('input', applyFiltersAndSort);


        /* =====================================================
           KUNCI FAVORIT
        ====================================================== */

        function makeFavoriteKey(place, latitude, longitude) {
            const id = place.place_id || place.id;
            const geo = Number(latitude).toFixed(5) + ',' + Number(longitude).toFixed(5);
            if (id) return ('pid:' + id + '|' + geo).slice(0, 190);
            return ('geo:' + geo + '|' + String(place.name || 'tempat').toLowerCase().trim().slice(0, 80)).slice(0, 190);
        }


        /* =====================================================
           TAMPILKAN MARKER TEMPAT (yang dipilih)
        ====================================================== */

        function showSelectedPlace(place, category) {

            // Hapus marker lama sekaligus rute lama
            clearPlaceMarkers();

            const icon = createPlaceIcon(category);
            const latitude = parseFloat(place.latitude);
            const longitude = parseFloat(place.longitude);

            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                alert('Koordinat tempat ini tidak valid.');
                return;
            }

            const marker = L.marker([latitude, longitude], { icon: icon });

            const categoryName = categoryLabel(category);
            const rating = Number(place.rating || 0);
            const reviewCount = Number(place.user_rating_count || 0);
            const fullStars = Math.min(5, Math.max(0, Math.round(rating)));
            const starsHtml = '★'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
            const googleMapsUrl = place.google_maps_url ||
                `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.name + ' ' + (place.address || ''))}`;
            const directionUrl = `https://www.google.com/maps/dir/?api=1&destination=${latitude},${longitude}`;

            const favoriteKey = makeFavoriteKey(place, latitude, longitude);

            const favoriteButton = `
                <button
                    type="button"
                    class="favorite-toggle-btn"
                    data-place-key="${escapeHtml(favoriteKey)}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:7px 10px;background:#fff1f2;color:#dc2626;border:1px solid #fecdd3;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;"
                >
                    <span class="favorite-heart">♡</span>
                    <span class="favorite-label">Memeriksa...</span>
                </button>
            `;

            marker.bindPopup(`
                <div class="place-popup-title" style="font-size:14px;font-weight:700;margin-bottom:2px;">
                    ${escapeHtml(place.name)}
                </div>

                <div class="place-popup-type" style="font-size:11.5px;color:#64748b;">
                    ${categoryName} • ${escapeHtml(place.type || '')}
                    ${place.distance !== undefined ? ` • ${place.distance} km` : ''}
                </div>

                <div style="margin-top:6px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                    <span style="display:inline-flex;align-items:center;gap:2px;background:#fef3c7;color:#92400e;padding:1px 6px;border-radius:5px;font-weight:700;font-size:12px;">
                        ⭐ ${rating.toFixed(1)}/5
                    </span>
                    <span style="color:#d97706;font-size:11px;letter-spacing:0.5px;">
                        ${starsHtml}
                    </span>
                    <span style="font-size:11.5px;color:#64748b;">
                        (${reviewCount.toLocaleString('id-ID')} ulasan Google)
                    </span>
                </div>

                ${place.address ? `
                    <div class="place-popup-address" style="margin-top:6px;font-size:11.5px;color:#475569;">
                        📍 ${escapeHtml(place.address)}
                    </div>
                ` : ''}

                <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap;">
                    <a href="${escapeHtml(googleMapsUrl)}" target="_blank" rel="noopener noreferrer"
                        style="display:inline-flex;align-items:center;gap:4px;padding:4px 8px;background:#1a73e8;color:#fff;border-radius:5px;font-size:11px;font-weight:600;text-decoration:none;">
                        <svg viewBox="0 0 24 24" width="11" height="11" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        Google Maps ↗
                    </a>
                    <button type="button" class="route-open-btn"
                        style="display:inline-flex;align-items:center;gap:4px;padding:4px 8px;background:#fff;color:#1a73e8;border:1px solid #1a73e8;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;">
                        🧭 Rute
                    </button>
                    ${favoriteButton}
                </div>
            `);

            const ratingValue =
                place.rating !== null && place.rating !== undefined && Number.isFinite(Number(place.rating))
                    ? Number(place.rating)
                    : null;

            const favoritePayload = {
                place_key: favoriteKey,
                name: place.name || 'Tempat tanpa nama',
                category: category,
                type: place.type || null,
                address: place.address || null,
                latitude: latitude,
                longitude: longitude,
                rating: ratingValue,
                user_rating_count: Number(place.user_rating_count) || 0,
                google_maps_url: googleMapsUrl,
                province: currentProvince ? currentProvince.name : null,
                city: currentCity ? currentCity.name : null
            };

            marker.on('popupopen', function (e) {
                const popupEl = e.popup.getElement();
                if (!popupEl) return;

                /* ---- Tombol RUTE (garis langsung di peta) ---- */
                const routeBtn = popupEl.querySelector('.route-open-btn');
                if (routeBtn) {
                    routeBtn.onclick = function (ev) {
                        ev.stopPropagation();
                        map.closePopup();
                        openRoutePanel({
                            lat: latitude,
                            lng: longitude,
                            name: place.name || 'Tempat tujuan',
                            googleUrl: directionUrl
                        });
                    };
                }

                /* ---- Tombol FAVORIT ---- */
                const btn = popupEl.querySelector('.favorite-toggle-btn');
                if (!btn) return;

                const heart = btn.querySelector('.favorite-heart');
                const label = btn.querySelector('.favorite-label');
                let busy = false;

                function render() {
                    const fav = isLoggedIn && favoriteKeys.has(favoriteKey);
                    heart.textContent = fav ? '♥' : '♡';
                    if (!isLoggedIn) {
                        label.textContent = 'Masuk untuk simpan';
                    } else if (busy) {
                        label.textContent = 'Menyimpan...';
                    } else {
                        label.textContent = fav ? 'Tersimpan' : 'Simpan Favorit';
                    }
                    btn.classList.toggle('is-saved', fav);
                    btn.disabled = busy;
                }

                btn.onclick = async function (ev) {
                    ev.stopPropagation();
                    if (!isLoggedIn) {
                        showToast('Masuk dulu untuk menyimpan destinasi favorit.', { href: loginUrl, text: 'Masuk' });
                        return;
                    }
                    if (busy) return;
                    busy = true;
                    render();

                    try {
                        const response = await fetch(favoriteToggleUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(favoritePayload)
                        });

                        if (response.status === 401 || response.status === 419) {
                            showToast('Sesi berakhir. Silakan masuk kembali.', { href: loginUrl, text: 'Masuk' }, true);
                            return;
                        }

                        const data = await response.json().catch(function () { return {}; });

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Gagal menyimpan favorit.');
                        }

                        if (data.favorited) {
                            favoriteKeys.add(favoriteKey);
                            showToast('Tersimpan di Destinasi Favorit Saya.',
                                profileUrl ? { href: profileUrl, text: 'Lihat' } : null);
                        } else {
                            favoriteKeys.delete(favoriteKey);
                            showToast('Dihapus dari Destinasi Favorit.');
                        }

                    } catch (err) {
                        console.error('Favorit:', err);
                        showToast(err.message || 'Gagal menyimpan favorit.', null, true);
                    } finally {
                        busy = false;
                        render();
                    }
                };

                render();
            });

            marker.addTo(map);
            placeMarkers.push(marker);
            selectedPlaceMarker = marker;

            map.flyTo([latitude, longitude], 16, { animate: true, duration: 1.5 });
            marker.openPopup();

            clearPlaces.style.display = 'block';
        }


        /* =====================================================
           HAPUS MARKER TEMPAT
        ====================================================== */

        function clearPlaceMarkers() {
            placeMarkers.forEach(marker => map.removeLayer(marker));
            placeMarkers = [];
            selectedPlaceMarker = null;
            clearPlaces.style.display = 'none';

            // Rute ikut dihapus karena tujuannya sudah tidak ada
            closeRoutePanel();
        }

        clearPlaces.addEventListener('click', function () {
            clearPlaceMarkers();
        });


        /* =====================================================
           RUTE DI PETA (OSRM - routing.openstreetmap.de)
           - Mobil  : profil mobil
           - Motor  : jaringan jalan mobil, estimasi waktu disesuaikan
           - Sepeda : profil sepeda
           - Jalan  : profil pejalan kaki
        ====================================================== */

        const ROUTE_BASE = 'https://routing.openstreetmap.de/';

        const routeModes = {
            car: {
                label: 'Mobil',
                icon: '🚗',
                profile: 'routed-car',
                color: '#2563eb',
                dash: null,
                speedKmh: null,        // pakai durasi dari server
                durationFactor: 1,
                note: ''
            },
            motor: {
                label: 'Motor',
                icon: '🏍️',
                profile: 'routed-car',
                color: '#ea580c',
                dash: null,
                speedKmh: null,
                durationFactor: 0.85,  // motor biasanya sedikit lebih cepat di kota
                note: 'Jalur memakai jaringan jalan mobil. Waktu hanya perkiraan.'
            },
            bike: {
                label: 'Sepeda',
                icon: '🚲',
                profile: 'routed-bike',
                color: '#16a34a',
                dash: null,
                speedKmh: 15,
                durationFactor: 1,
                note: 'Estimasi waktu dihitung dengan kecepatan rata-rata 15 km/jam.'
            },
            foot: {
                label: 'Jalan kaki',
                icon: '🚶',
                profile: 'routed-foot',
                color: '#9333ea',
                dash: '2 10',
                speedKmh: 5,
                durationFactor: 1,
                note: 'Estimasi waktu dihitung dengan kecepatan rata-rata 5 km/jam.'
            }
        };

        function formatDistance(meters) {
            const m = Number(meters);
            if (!Number.isFinite(m)) return '-';
            if (m < 1000) return Math.round(m) + ' m';
            return (m / 1000).toFixed(m < 10000 ? 1 : 0) + ' km';
        }

        function formatDuration(seconds) {
            const s = Number(seconds);
            if (!Number.isFinite(s)) return '-';
            const totalMinutes = Math.max(1, Math.round(s / 60));
            if (totalMinutes < 60) return totalMinutes + ' mnt';
            const h = Math.floor(totalMinutes / 60);
            const mnt = totalMinutes % 60;
            if (h >= 24) {
                const d = Math.floor(h / 24);
                return d + ' hr ' + (h % 24) + ' j';
            }
            return h + ' j' + (mnt ? ' ' + mnt + ' mnt' : '');
        }

        function stepInstruction(step) {
            const m = step.maneuver || {};
            const road = step.name ? ' ke ' + step.name : '';
            const modMap = {
                'left': 'kiri',
                'right': 'kanan',
                'slight left': 'serong kiri',
                'slight right': 'serong kanan',
                'sharp left': 'tajam ke kiri',
                'sharp right': 'tajam ke kanan',
                'straight': 'lurus',
                'uturn': 'putar balik'
            };
            const mod = modMap[m.modifier] || '';

            switch (m.type) {
                case 'depart':
                    return 'Mulai perjalanan' + (step.name ? ' di ' + step.name : '');
                case 'arrive':
                    return 'Anda tiba di tujuan';
                case 'turn':
                    if (m.modifier === 'straight') return 'Lurus' + road;
                    if (m.modifier === 'uturn') return 'Putar balik';
                    return 'Belok ' + mod + road;
                case 'end of road':
                    if (m.modifier === 'uturn') return 'Putar balik di ujung jalan';
                    return 'Di ujung jalan belok ' + mod + road;
                case 'fork':
                    return 'Ambil percabangan ' + (mod || 'lurus') + road;
                case 'merge':
                    return 'Bergabung' + road;
                case 'on ramp':
                    return 'Masuk jalur' + road;
                case 'off ramp':
                    return 'Keluar dari jalur' + road;
                case 'roundabout':
                case 'rotary':
                    return 'Masuk bundaran' + (m.exit ? ', ambil exit ke-' + m.exit : '') + road;
                case 'roundabout turn':
                    return 'Di bundaran belok ' + mod + road;
                case 'new name':
                case 'continue':
                    return 'Lanjut' + (road || ' lurus');
                default:
                    return 'Lanjut' + road;
            }
        }

        function clearRouteLayers() {
            if (routeLayer) {
                map.removeLayer(routeLayer);
                routeLayer = null;
            }
            if (routeOriginMarker) {
                map.removeLayer(routeOriginMarker);
                routeOriginMarker = null;
            }
        }

        function closeRoutePanel() {
            routeRequestId++;           // batalkan request yang masih jalan
            routeDestination = null;
            clearRouteLayers();
            if (routePanel) routePanel.classList.remove('show');
            if (routeSummary) routeSummary.innerHTML = '';
            if (routeSteps) routeSteps.innerHTML = '';
        }

        function setRouteModeButtons() {
            routeModesWrap.querySelectorAll('.route-mode-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.mode === routeMode);
            });
        }

        function openRoutePanel(destination) {
            routeDestination = destination;

            routeTitle.textContent = '🧭 Rute ke ' + destination.name;
            routeGmaps.href = destination.googleUrl || '#';

            // Jika kota sudah dipilih, "pusat kota" tersedia sebagai titik awal
            const cityOption = routeOriginSelect.querySelector('option[value="city"]');
            if (cityOption) {
                const cityName = currentCity ? currentCity.name : 'kota';
                cityOption.textContent = '🏙️ Pusat ' + cityName;
            }

            routeOriginSelect.value = routeOriginType;
            setRouteModeButtons();

            routePanel.classList.add('show');
            loadRoute();
        }

        async function getUserLocation() {
            if (userLocation) return userLocation;

            if (!('geolocation' in navigator)) {
                throw new Error('Browser tidak mendukung GPS.');
            }

            return new Promise(function (resolve, reject) {
                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        userLocation = {
                            lat: pos.coords.latitude,
                            lng: pos.coords.longitude
                        };
                        resolve(userLocation);
                    },
                    function (err) {
                        reject(new Error(
                            err && err.code === 1
                                ? 'Izin lokasi ditolak.'
                                : 'Lokasi Anda tidak dapat ditentukan.'
                        ));
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
                );
            });
        }

        function getCityOrigin() {
            if (currentCity && Number.isFinite(Number(currentCity.latitude)) && Number.isFinite(Number(currentCity.longitude))) {
                return {
                    lat: Number(currentCity.latitude),
                    lng: Number(currentCity.longitude),
                    label: 'Pusat ' + currentCity.name
                };
            }
            return null;
        }

        async function resolveRouteOrigin() {

            if (routeOriginType === 'me') {
                try {
                    const loc = await getUserLocation();
                    return { lat: loc.lat, lng: loc.lng, label: 'Lokasi saya' };
                } catch (err) {
                    const fallback = getCityOrigin();
                    if (fallback) {
                        routeOriginType = 'city';
                        routeOriginSelect.value = 'city';
                        showToast((err.message || 'Lokasi tidak tersedia.') + ' Memakai pusat kota sebagai titik awal.', null, true);
                        return fallback;
                    }
                    throw err;
                }
            }

            const cityOrigin = getCityOrigin();
            if (cityOrigin) return cityOrigin;

            throw new Error('Pusat kota belum tersedia. Pilih kota terlebih dahulu.');
        }

        function renderRouteMessage(type, html) {
            routeSummary.innerHTML = `<div class="route-msg ${type}">${html}</div>`;
            routeSteps.innerHTML = '';
        }

        async function loadRoute() {

            if (!routeDestination) return;

            const requestId = ++routeRequestId;
            const mode = routeModes[routeMode];

            clearRouteLayers();
            renderRouteMessage('loading', '⏳ Mencari rute ' + mode.label.toLowerCase() + '...');

            try {
                const origin = await resolveRouteOrigin();
                if (requestId !== routeRequestId) return;

                const dest = routeDestination;

                const url =
                    ROUTE_BASE + mode.profile + '/route/v1/driving/' +
                    origin.lng + ',' + origin.lat + ';' +
                    dest.lng + ',' + dest.lat + '?' +
                    new URLSearchParams({
                        overview: 'full',
                        geometries: 'geojson',
                        steps: 'true',
                        alternatives: 'false'
                    });

                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error('Layanan rute tidak merespons (' + response.status + ').');
                }

                const data = await response.json();
                if (requestId !== routeRequestId) return;

                if (data.code !== 'Ok' || !data.routes || data.routes.length === 0) {
                    throw new Error(
                        data.code === 'NoRoute'
                            ? 'Rute ' + mode.label.toLowerCase() + ' tidak ditemukan (mungkin terpisah laut/pulau atau tidak ada akses jalan).'
                            : 'Rute tidak ditemukan.'
                    );
                }

                drawRoute(data.routes[0], origin, mode);

            } catch (error) {
                if (requestId !== routeRequestId) return;
                console.error('Rute:', error);
                renderRouteMessage('error', '❌ ' + escapeHtml(error.message || 'Gagal mengambil rute.'));
            }
        }

        function drawRoute(route, origin, mode) {

            clearRouteLayers();

            const distance = route.distance;

            // Hitung durasi sesuai kendaraan
            let duration;
            if (mode.speedKmh) {
                duration = (distance / 1000) / mode.speedKmh * 3600;
            } else {
                duration = route.duration * mode.durationFactor;
            }

            // Garis rute: lapisan putih (casing) + lapisan warna
            const casing = L.geoJSON(route.geometry, {
                style: { color: '#ffffff', weight: 10, opacity: 0.95, lineCap: 'round', lineJoin: 'round' },
                interactive: false
            });

            const line = L.geoJSON(route.geometry, {
                style: {
                    color: mode.color,
                    weight: 6,
                    opacity: 0.95,
                    lineCap: 'round',
                    lineJoin: 'round',
                    dashArray: mode.dash || null
                },
                interactive: false
            });

            routeLayer = L.layerGroup([casing, line]).addTo(map);

            // Marker titik awal
            routeOriginMarker = L.marker([origin.lat, origin.lng], {
                icon: L.divIcon({
                    className: '',
                    html: '<div class="route-origin-marker"></div>',
                    iconSize: [18, 18],
                    iconAnchor: [9, 9]
                }),
                keyboard: false
            }).addTo(map);

            routeOriginMarker.bindTooltip(escapeHtml(origin.label), { direction: 'top', offset: [0, -8] });

            // Pastikan marker tujuan tetap di atas garis
            if (selectedPlaceMarker) selectedPlaceMarker.setZIndexOffset(1000);

            // Zoom agar seluruh rute terlihat
            map.fitBounds(line.getBounds(), {
                padding: [70, 70],
                maxZoom: 17,
                animate: true,
                duration: 1.2
            });

            // Ringkasan
            routeSummary.innerHTML = `
                <div class="route-info-card" style="border-left:4px solid ${mode.color};">
                    <div>
                        <div class="route-info-time">${escapeHtml(formatDuration(duration))}</div>
                        <div class="route-info-dist">${escapeHtml(formatDistance(distance))} • dari ${escapeHtml(origin.label)}</div>
                    </div>
                    <div class="route-info-icon">${mode.icon}</div>
                </div>
                ${mode.note ? `<div class="route-note">${escapeHtml(mode.note)}</div>` : ''}
            `;

            // Petunjuk belokan
            const steps = [];
            (route.legs || []).forEach(leg => {
                (leg.steps || []).forEach(step => steps.push(step));
            });

            routeSteps.innerHTML = steps.map(step => `
                <div class="route-step">
                    <span>${escapeHtml(stepInstruction(step))}</span>
                    <span class="route-step-dist">${step.distance > 0 ? escapeHtml(formatDistance(step.distance)) : ''}</span>
                </div>
            `).join('');
        }

        /* Event: pilih kendaraan */
        routeModesWrap.addEventListener('click', function (event) {
            const btn = event.target.closest('.route-mode-btn');
            if (!btn) return;

            const newMode = btn.dataset.mode;
            if (!routeModes[newMode] || newMode === routeMode) return;

            routeMode = newMode;
            setRouteModeButtons();
            loadRoute();
        });

        /* Event: ganti titik awal */
        routeOriginSelect.addEventListener('change', function () {
            routeOriginType = this.value;
            loadRoute();
        });

        /* Event: tutup rute */
        routeClose.addEventListener('click', function () {
            closeRoutePanel();
        });


        /* =====================================================
           TOAST
        ====================================================== */

        let toastTimer = null;

        function showToast(message, action, isError) {
            const el = document.getElementById('toast');
            if (!el) return;

            el.className = isError ? 'show error' : 'show';
            el.innerHTML = '<span>' + escapeHtml(message) + '</span>';

            if (action && action.href) {
                const link = document.createElement('a');
                link.href = action.href;
                link.textContent = action.text || 'Buka';
                el.appendChild(link);
            }

            clearTimeout(toastTimer);
            toastTimer = setTimeout(function () {
                el.className = '';
            }, 4500);
        }


        /* =====================================================
           ESCAPE HTML
        ====================================================== */

        function escapeHtml(value) {
            if (value === null || value === undefined) return '';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }


        /* =====================================================
           INITIAL
        ====================================================== */

        currentCountry = indonesiaCountry;
        searchInput.value = '';
        try {
            Object.keys(localStorage)
                .filter(key => key.startsWith('jelajah_remote_'))
                .forEach(key => localStorage.removeItem(key));
        } catch (error) {
            console.warn('Cache lama negara lain tidak dapat dihapus:', error);
        }
        renderProvinceResults();

    </script>

</body>

</html>