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

        </div>


        <!-- SEARCH TEMPAT WISATA / KULINER -->
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
                placeholder="🔎 Cari nama tempat wisata atau kuliner..."
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
        // Filter ini menjadi pengaman tambahan apabila database lama masih
        // memiliki baris provinsi dari negara lain.
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

        // Sistem hanya fokus pada Indonesia. Negara lain tidak dipakai
        // pada hirarki pencarian maupun navigasi peta.
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

        /* OSM */
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

        /* LOCATION ELEMENT */
        const locationInfo = document.getElementById('locationInfo');
        const locationName = document.getElementById('locationName');
        const locationAddress = document.getElementById('locationAddress');
        const cityWikiContainer = document.getElementById('cityWikiContainer');
        const cityWikiLink = document.getElementById('cityWikiLink');
        const cityWikiLabel = document.getElementById('cityWikiLabel');

        /* CATEGORY ELEMENT */
        const categoryOptions = document.getElementById('categoryOptions');
        const wisataButton = document.getElementById('wisataButton');
        const kulinerButton = document.getElementById('kulinerButton');
        const placesLoading = document.getElementById('placesLoading');
        const clearPlaces = document.getElementById('clearPlaces');
        const placeSearchPanel = document.getElementById('placeSearchPanel');
        const placeSearchInput = document.getElementById('placeSearchInput');
        const placeSearchResults = document.getElementById('placeSearchResults');

        const placeRatingSort = document.getElementById('placeRatingSort');
        const placeRatingNotice = document.getElementById('placeRatingNotice');
        const placeRatingFilter = document.getElementById('placeRatingFilter');
        const placeResultCount = document.getElementById('placeResultCount');


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


        /* ICON KOTA */
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


        /* =====================================================
           SEARCH OPEN
        ====================================================== */

        searchToggle.addEventListener('click', function () {

            searchPanel.style.display = 'block';

            searchToggle.style.display = 'none';

            searchInput.focus();

            renderProvinceResults();

        });


        /* SEARCH CLOSE */
        closeSearch.addEventListener('click', function () {

            searchPanel.style.display = 'none';

            searchToggle.style.display = 'block';

        });


        /* ESC */
        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {

                searchPanel.style.display = 'none';

                searchToggle.style.display = 'block';

            }

        });


        /* =====================================================
           PROVINSI INDONESIA
        ====================================================== */

        function renderProvinceResults(keyword = '') {

            searchTitle.textContent = '🔍 Cari Provinsi Indonesia';

            searchMode.textContent = 'Indonesia';

            searchInput.placeholder = 'Cari provinsi Indonesia...';

            backToProvince.style.display = 'none';

            const search =
                String(keyword || '').toLowerCase().trim();

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


        function renderCityResults(keyword = '') {

            if (!currentProvince) {
                renderProvinceResults('');
                return;
            }

            searchTitle.textContent = '🔍 Cari Kota / Kabupaten';

            searchMode.textContent = currentProvince.name;

            searchInput.placeholder = 'Cari kota atau kabupaten...';

            backToProvince.style.display = 'block';

            backToProvince.textContent = '← Kembali ke Provinsi';

            const search =
                String(keyword || '').toLowerCase().trim();

            const filtered = cities.filter(city => {
                return String(city.province_id) === String(currentProvince.id) &&
                    String(city.name || '').toLowerCase().includes(search);
            });

            searchResults.innerHTML = '';

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Kota / Kabupaten tidak ditemukan di ${escapeHtml(currentProvince.name)}.
                    </div>
                `;
                return;
            }

            filtered.forEach(city => {
                const item = document.createElement('div');
                item.className = 'search-result';

                item.innerHTML = `
                    <div class="result-name">
                        📍 ${escapeHtml(city.name)}
                    </div>
                    <div class="result-info">
                        ${escapeHtml(city.type || 'Kota/Kabupaten')} • ${escapeHtml(currentProvince.name)}, Indonesia
                    </div>
                `;

                item.addEventListener('click', function () {
                    selectCity(city);
                });

                searchResults.appendChild(item);
            });
        }


        async function selectProvince(province) {

            currentProvince = {
                ...province,
                latitude: null,
                longitude: null
            };
            currentCity = null;

            clearPlaceMarkers();

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

            // Selalu resolusikan ulang koordinat provinsi melalui backend.
            // Ini mencegah koordinat database lama yang salah dipakai.
            await searchProvinceOpenStreetMap(currentProvince);

            // Daftar kota langsung tersedia setelah provinsi dipilih.
            searchInput.value = '';
            renderCityResults('');
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
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                console.log('Geocoding provinsi:', data);

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Provinsi tidak ditemukan.'
                    );
                }

                showProvinceOnMap(
                    parseFloat(data.latitude),
                    parseFloat(data.longitude),
                    province.name,
                    data.display_name
                );

            } catch (error) {
                console.error('Geocoding provinsi:', error);

                // Jangan gunakan koordinat database lama sebagai fallback karena
                // nilainya bisa tidak sesuai dengan wilayah yang dipilih.
                // Marker hanya boleh dibuat dari koordinat hasil resolver wilayah.
                locationInfo.style.display = 'block';
                locationName.textContent = province.name;
                locationAddress.textContent =
                    'Koordinat provinsi sedang tidak tersedia. Coba pilih lagi.';
            }
        }


        /* =====================================================
           TAMPILKAN PROVINSI
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


            currentProvinceMarker
                .bindPopup(`

                    <div class="place-popup-title">
                        ${name}
                    </div>

                    <div class="place-popup-type">
                        ${currentCountry ? currentCountry.name : 'Indonesia'}
                    </div>

                `);

            // Tautan informasi khusus untuk provinsi yang memiliki halaman Wikipedia.
            // Provinsi lainnya tetap memakai perilaku marker dan zoom seperti biasa.
            const provinceWikipediaLinks = {
                'sumatera selatan': {
                    url: 'https://id.wikipedia.org/wiki/Sumatera_Selatan',
                    label: 'Sumatera Selatan'
                },
                'sumatera barat': {
                    url: 'https://id.wikipedia.org/wiki/Sumatera_Barat',
                    label: 'Sumatera Barat'
                },
                'sumatera utara': {
                    url: 'https://id.wikipedia.org/wiki/Sumatera_Utara',
                    label: 'Sumatera Utara'
                },
                'aceh': {
                    url: 'https://id.wikipedia.org/wiki/Aceh',
                    label: 'Aceh'
                },
                'bengkulu': {
                    url: 'https://id.wikipedia.org/wiki/Bengkulu',
                    label: 'Bengkulu'
                },
                'bali': {
                    url: 'https://id.wikipedia.org/wiki/Bali',
                    label: 'Bali'
                },
                'banten': {
                    url: 'https://id.wikipedia.org/wiki/Banten',
                    label: 'Banten'
                },
                'daerah istimewa yogyakarta': {
                    url: 'https://id.wikipedia.org/wiki/Daerah_Istimewa_Yogyakarta',
                    label: 'Daerah Istimewa Yogyakarta'
                },
                'di yogyakarta': {
                    url: 'https://id.wikipedia.org/wiki/Daerah_Istimewa_Yogyakarta',
                    label: 'Daerah Istimewa Yogyakarta'
                },
                'daerah khusus ibukota jakarta': {
                    url: 'https://id.wikipedia.org/wiki/Daerah_Khusus_Ibukota_Jakarta',
                    label: 'Daerah Khusus Ibukota Jakarta'
                },
                'dki jakarta': {
                    url: 'https://id.wikipedia.org/wiki/Daerah_Khusus_Ibukota_Jakarta',
                    label: 'Daerah Khusus Ibukota Jakarta'
                },
                'jakarta': {
                    url: 'https://id.wikipedia.org/wiki/Daerah_Khusus_Ibukota_Jakarta',
                    label: 'Daerah Khusus Ibukota Jakarta'
                },
                'gorontalo': {
                    url: 'https://id.wikipedia.org/wiki/Gorontalo',
                    label: 'Gorontalo'
                },
                'jambi': {
                    url: 'https://id.wikipedia.org/wiki/Jambi',
                    label: 'Jambi'
                },
                'jawa barat': {
                    url: 'https://id.wikipedia.org/wiki/Jawa_Barat',
                    label: 'Jawa Barat'
                },
                'jawa tengah': {
                    url: 'https://id.wikipedia.org/wiki/Jawa_Tengah',
                    label: 'Jawa Tengah'
                },
                'jawa timur': {
                    url: 'https://id.wikipedia.org/wiki/Jawa_Timur',
                    label: 'Jawa Timur'
                },
                'kalimantan barat': {
                    url: 'https://id.wikipedia.org/wiki/Kalimantan_Barat',
                    label: 'Kalimantan Barat'
                },
                'kalimantan selatan': {
                    url: 'https://id.wikipedia.org/wiki/Kalimantan_Selatan',
                    label: 'Kalimantan Selatan'
                },
                'kalimantan tengah': {
                    url: 'https://id.wikipedia.org/wiki/Kalimantan_Tengah',
                    label: 'Kalimantan Tengah'
                },
                'kalimantan timur': {
                    url: 'https://id.wikipedia.org/wiki/Kalimantan_Timur',
                    label: 'Kalimantan Timur'
                },
                'kalimantan utara': {
                    url: 'https://id.wikipedia.org/wiki/Kalimantan_Utara',
                    label: 'Kalimantan Utara'
                },
                'kepulauan bangka belitung': {
                    url: 'https://id.wikipedia.org/wiki/Kepulauan_Bangka_Belitung',
                    label: 'Kepulauan Bangka Belitung'
                },
                'bangka belitung': {
                    url: 'https://id.wikipedia.org/wiki/Kepulauan_Bangka_Belitung',
                    label: 'Kepulauan Bangka Belitung'
                },
                'kepulauan riau': {
                    url: 'https://id.wikipedia.org/wiki/Kepulauan_Riau',
                    label: 'Kepulauan Riau'
                },
                'lampung': {
                    url: 'https://id.wikipedia.org/wiki/Lampung',
                    label: 'Lampung'
                },
                'maluku': {
                    url: 'https://id.wikipedia.org/wiki/Maluku',
                    label: 'Maluku'
                },
                'maluku utara': {
                    url: 'https://id.wikipedia.org/wiki/Maluku_Utara',
                    label: 'Maluku Utara'
                },
                'nusa tenggara barat': {
                    url: 'https://id.wikipedia.org/wiki/Nusa_Tenggara_Barat',
                    label: 'Nusa Tenggara Barat'
                },
                'ntb': {
                    url: 'https://id.wikipedia.org/wiki/Nusa_Tenggara_Barat',
                    label: 'Nusa Tenggara Barat'
                },
                'nusa tenggara timur': {
                    url: 'https://id.wikipedia.org/wiki/Nusa_Tenggara_Timur',
                    label: 'Nusa Tenggara Timur'
                },
                'ntt': {
                    url: 'https://id.wikipedia.org/wiki/Nusa_Tenggara_Timur',
                    label: 'Nusa Tenggara Timur'
                },
                'papua': {
                    url: 'https://id.wikipedia.org/wiki/Papua',
                    label: 'Papua'
                },
                'papua barat': {
                    url: 'https://id.wikipedia.org/wiki/Papua_Barat',
                    label: 'Papua Barat'
                },
                'papua barat daya': {
                    url: 'https://id.wikipedia.org/wiki/Papua_Barat_Daya',
                    label: 'Papua Barat Daya'
                },
                'papua pegunungan': {
                    url: 'https://id.wikipedia.org/wiki/Papua_Pegunungan',
                    label: 'Papua Pegunungan'
                },
                'papua selatan': {
                    url: 'https://id.wikipedia.org/wiki/Papua_Selatan',
                    label: 'Papua Selatan'
                },
                'papua tengah': {
                    url: 'https://id.wikipedia.org/wiki/Papua_Tengah',
                    label: 'Papua Tengah'
                },
                'riau': {
                    url: 'https://id.wikipedia.org/wiki/Riau',
                    label: 'Riau'
                },
                'sulawesi barat': {
                    url: 'https://id.wikipedia.org/wiki/Sulawesi_Barat',
                    label: 'Sulawesi Barat'
                },
                'sulawesi selatan': {
                    url: 'https://id.wikipedia.org/wiki/Sulawesi_Selatan',
                    label: 'Sulawesi Selatan'
                },
                'sulawesi tengah': {
                    url: 'https://id.wikipedia.org/wiki/Sulawesi_Tengah',
                    label: 'Sulawesi Tengah'
                },
                'sulawesi tenggara': {
                    url: 'https://id.wikipedia.org/wiki/Sulawesi_Tenggara',
                    label: 'Sulawesi Tenggara'
                },
                'sulawesi utara': {
                    url: 'https://id.wikipedia.org/wiki/Sulawesi_Utara',
                    label: 'Sulawesi Utara'
                }
            };

            const provinceWikipedia = provinceWikipediaLinks[normalizeProvinceName(name)];

            if (provinceWikipedia) {
                currentProvinceMarker.bindTooltip(
                    `Klik marker untuk membuka informasi ${provinceWikipedia.label}`,
                    {
                        direction: 'top',
                        offset: [0, -28],
                        opacity: 0.95
                    }
                );

                currentProvinceMarker.on('click', function () {
                    window.open(
                        provinceWikipedia.url,
                        '_blank',
                        'noopener,noreferrer'
                    );
                });
            }

            currentProvinceMarker.openPopup();

            map.flyTo(
                [latitude, longitude],
                8,
                {
                    animate: true,
                    duration: 1.8
                }
            );

            locationInfo.style.display = 'block';

            locationName.textContent = name;

            locationAddress.textContent = address;

            searchInput.value = '';

        }


        /* =====================================================
           PILIH KOTA
        ====================================================== */

        async function selectCity(city) {

            currentCity = {
                ...city,
                latitude: null,
                longitude: null
            };

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
            locationInfo.style.display = 'none';

            // Selalu minta koordinat kota yang sudah dibatasi oleh provinsi aktif.
            await searchCityOpenStreetMap(currentCity);
        }


        /* =====================================================
           CARI KOTA OSM / DATA WILAYAH
        ====================================================== */

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
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                console.log('Geocoding kota/kabupaten:', data);

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message ||
                        'Kota/kabupaten tidak ditemukan.'
                    );
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
           TAMPILKAN KOTA
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


            currentCityMarker
                .bindPopup(`

                    <div class="place-popup-title">
                        ${name}
                    </div>

                    <div class="place-popup-type">
                        ${address}
                    </div>

                `);

            currentCityMarker.openPopup();

            map.flyTo(
                [latitude, longitude],
                13,
                {
                    animate: true,
                    duration: 1.8
                }
            );

            locationInfo.style.display = 'block';

            locationName.textContent = name;

            locationAddress.textContent = address;

            // Setelah kota berhasil dipilih,
            // tombol Wisata dan Kuliner muncul.
            categoryOptions.style.display = 'flex';

        }


        /* =====================================================
           INPUT SEARCH
        ====================================================== */

        searchInput.addEventListener('input', function () {

            if (currentProvince) {
                renderCityResults(this.value);
            } else {
                renderProvinceResults(this.value);
            }
        });


        /* =====================================================
           TOMBOL KEMBALI HIERARKI
        ====================================================== */

        backToProvince.addEventListener('click', function () {

            // Dari kota kembali ke daftar seluruh provinsi Indonesia.
            if (currentCity && currentProvince) {
                if (currentCityMarker) {
                    map.removeLayer(currentCityMarker);
                    currentCityMarker = null;
                }

                clearPlaceMarkers();
                currentCity = null;
                currentProvince = null;

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

            // Dari provinsi kembali ke seluruh provinsi Indonesia.
            currentProvince = null;
            currentCity = null;

            if (currentProvinceMarker) {
                map.removeLayer(currentProvinceMarker);
                currentProvinceMarker = null;
            }

            if (currentCityMarker) {
                map.removeLayer(currentCityMarker);
                currentCityMarker = null;
            }

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
        });


        /* =====================================================
           WISATA
        ====================================================== */

        wisataButton.addEventListener('click', function () {

            if (!currentCity) {
                return;
            }

            loadPlaces('wisata');
        });


        /* KULINER */
        kulinerButton.addEventListener('click', function () {

            if (!currentCity) {
                return;
            }

            loadPlaces('kuliner');
        });


        /* =====================================================
           LOAD PLACES
           Data hanya dimasukkan ke daftar pencarian.
           Marker BELUM dibuat pada tahap ini.
        ====================================================== */

        async function loadPlaces(category) {

            if (!currentCity) {
                return;
            }

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
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!data.success) {

                    throw new Error(
                        data.message ||
                        'Gagal mengambil data tempat.'
                    );
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
                    <div
                        style="
                            padding:12px;
                            color:#c62828;
                            background:#ffebee;
                            border-radius:8px;
                            font-size:13px;
                        "
                    >
                        ❌ ${escapeHtml(
                            error.message ||
                            'Gagal mengambil data tempat.'
                        )}
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
                    <div
                        style="
                            padding:14px;
                            color:#64748b;
                            background:#f8fafc;
                            border:1px dashed #cbd5e1;
                            border-radius:9px;
                            font-size:12.5px;
                            text-align:center;
                        "
                    >
                        ${
                            category === 'wisata'
                                ? 'Tidak ada tempat wisata yang sesuai dengan filter atau pencarian.'
                                : 'Tidak ada tempat kuliner yang sesuai dengan filter atau pencarian.'
                        }
                    </div>
                `;

                return;
            }

            placeSearchResults.innerHTML =
                places.map((place, index) => {

                    const distance =
                        place.distance !== undefined
                            ? `${place.distance} km`
                            : '';
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
                            style="
                                padding:11px 12px;
                                margin-bottom:7px;
                                background:white;
                                border:1px solid #e2e8f0;
                                border-radius:10px;
                                cursor:pointer;
                                transition:all .2s ease;
                                box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                            "
                        >
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;">
                                <div
                                    style="
                                        font-weight:700;
                                        font-size:13.5px;
                                        color:#1e293b;
                                        line-height:1.35;
                                    "
                                >
                                    ${category === 'wisata' ? '🏞️' : '🍜'}
                                    ${escapeHtml(place.name)}
                                </div>
                                ${distance ? `
                                    <span style="flex-shrink:0;background:#f1f5f9;color:#475569;font-size:11px;font-weight:600;padding:2px 6px;border-radius:6px;">
                                        ${distance}
                                    </span>
                                ` : ''}
                            </div>

                            <div
                                style="
                                    margin-top:3px;
                                    font-size:11.5px;
                                    color:#64748b;
                                "
                            >
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
                                    href="${googleMapsUrl}"
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

            const items =
                placeSearchResults.querySelectorAll('.place-search-item');

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
           FILTER & SORT TEMPAT (GOOGLE RATING & JARAK)
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

            // 1. Filter pencarian teks
            let filtered = loadedPlaces.filter(place => {
                if (!keyword) return true;
                const name = (place.name || '').toLowerCase();
                const type = (place.type || '').toLowerCase();
                const address = (place.address || '').toLowerCase();
                return name.includes(keyword) || type.includes(keyword) || address.includes(keyword);
            });

            // 2. Filter batas rating minimum / kategori rating
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

            // 3. Pengurutan (Rating tertinggi/terendah, ulasan terbanyak, atau terdekat)
            filtered.sort((a, b) => {
                const ar = (a.rating !== null && a.rating !== undefined && Number.isFinite(Number(a.rating))) ? Number(a.rating) : 0;
                const br = (b.rating !== null && b.rating !== undefined && Number.isFinite(Number(b.rating))) ? Number(b.rating) : 0;
                const ac = Number(a.user_rating_count) || 0;
                const bc = Number(b.user_rating_count) || 0;
                const ad = Number(a.distance) || 0;
                const bd = Number(b.distance) || 0;

                if (sortMode === 'rating-desc') {
                    // Rating tertinggi ke terendah (5.0 -> 1.0)
                    if (br !== ar) return br - ar;
                    if (bc !== ac) return bc - ac; // Tie-breaker: ulasan terbanyak
                    return ad - bd; // Tie-breaker: jarak terdekat
                } else if (sortMode === 'rating-asc') {
                    // Rating terendah ke tertinggi (1.0 -> 5.0)
                    if (ar !== br) return ar - br;
                    if (ac !== bc) return ac - bc;
                    return ad - bd;
                } else if (sortMode === 'reviews-desc') {
                    // Ulasan terbanyak
                    if (bc !== ac) return bc - ac;
                    return br - ar;
                } else {
                    // Default: Jarak terdekat
                    if (ad !== bd) return ad - bd;
                    return br - ar;
                }
            });

            if (placeResultCount) {
                placeResultCount.textContent = `${filtered.length} tempat`;
            }

            renderPlaceSearchResults(filtered, currentPlaceCategory);
        }

        if (placeRatingSort) {
            placeRatingSort.addEventListener('change', applyFiltersAndSort);
        }

        if (placeRatingFilter) {
            placeRatingFilter.addEventListener('change', applyFiltersAndSort);
        }

        if (placeSearchInput) {
            placeSearchInput.addEventListener('input', applyFiltersAndSort);
        }


        /* =====================================================
           KUNCI FAVORIT  (PERBAIKAN 1: fungsi yang sebelumnya hilang)
        ====================================================== */

        function makeFavoriteKey(place, latitude, longitude) {
            const id = place.place_id || place.id;
            const geo = Number(latitude).toFixed(5) + ',' + Number(longitude).toFixed(5);

            // Maksimal 190 karakter agar muat di kolom database
            if (id) {
                return ('pid:' + id + '|' + geo).slice(0, 190);
            }

            return (
                'geo:' + geo + '|' +
                String(place.name || 'tempat').toLowerCase().trim().slice(0, 80)
            ).slice(0, 190);
        }


        /* =====================================================
           TAMPILKAN MARKER HANYA TEMPAT YANG DIPILIH
        ====================================================== */

        function showSelectedPlace(place, category) {

            clearPlaceMarkers();

            let icon;

            if (category === 'wisata') {
                icon = createTourismIcon();
            } else {
                icon = createFoodIcon();
            }

            const latitude = parseFloat(place.latitude);

            const longitude = parseFloat(place.longitude);

            // PERBAIKAN 2: pengaman koordinat
            if (!Number.isFinite(latitude) || !Number.isFinite(longitude)) {
                alert('Koordinat tempat ini tidak valid.');
                return;
            }

            const marker =
                L.marker(
                    [latitude, longitude],
                    { icon: icon }
                );

            const categoryName =
                category === 'wisata'
                    ? '🏞️ Wisata'
                    : '🍜 Kuliner';

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
                    style="
                        display:inline-flex;
                        align-items:center;
                        gap:6px;
                        padding:7px 10px;
                        background:#fff1f2;
                        color:#dc2626;
                        border:1px solid #fecdd3;
                        border-radius:7px;
                        font-size:12px;
                        font-weight:700;
                        cursor:pointer;
                    "
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

                ${
                    place.address
                        ? `
                            <div class="place-popup-address" style="margin-top:6px;font-size:11.5px;color:#475569;">
                                📍 ${escapeHtml(place.address)}
                            </div>
                        `
                        : ''
                }

                <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap;">
                    <a
                        href="${googleMapsUrl}"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="display:inline-flex;align-items:center;gap:4px;padding:4px 8px;background:#1a73e8;color:#fff;border-radius:5px;font-size:11px;font-weight:600;text-decoration:none;"
                    >
                        <svg viewBox="0 0 24 24" width="11" height="11" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        Google Maps ↗
                    </a>
                    <a
                        href="${directionUrl}"
                        target="_blank"
                        rel="noopener noreferrer"
                        style="display:inline-flex;align-items:center;gap:4px;padding:4px 8px;background:#fff;color:#1a73e8;border:1px solid #1a73e8;border-radius:5px;font-size:11px;font-weight:600;text-decoration:none;"
                    >
                        🚗 Rute
                    </a>
                    ${favoriteButton}
                </div>
            `);

            // Data yang dikirim ke server saat tombol favorit ditekan
            const ratingValue =
                place.rating !== null &&
                place.rating !== undefined &&
                Number.isFinite(Number(place.rating))
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

            // Aktifkan tombol favorit di popup (tersimpan ke database)
            marker.on('popupopen', function (e) {
                const popupEl = e.popup.getElement();
                if (!popupEl) return;

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
                        showToast(
                            'Masuk dulu untuk menyimpan destinasi favorit.',
                            { href: loginUrl, text: 'Masuk' }
                        );
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
                            showToast(
                                'Sesi berakhir. Silakan masuk kembali.',
                                { href: loginUrl, text: 'Masuk' },
                                true
                            );
                            return;
                        }

                        const data = await response.json().catch(function () { return {}; });

                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Gagal menyimpan favorit.');
                        }

                        if (data.favorited) {
                            favoriteKeys.add(favoriteKey);
                            showToast(
                                'Tersimpan di Destinasi Favorit Saya.',
                                profileUrl ? { href: profileUrl, text: 'Lihat' } : null
                            );
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

            map.flyTo(
                [latitude, longitude],
                16,
                {
                    animate: true,
                    duration: 1.5
                }
            );

            marker.openPopup();

            clearPlaces.style.display = 'block';
        }


        /* =====================================================
           HAPUS MARKER TEMPAT
        ====================================================== */

        function clearPlaceMarkers() {

            placeMarkers.forEach(marker => {
                map.removeLayer(marker);
            });

            placeMarkers = [];
            selectedPlaceMarker = null;

            clearPlaces.style.display = 'none';
        }


        /* BUTTON HAPUS MARKER */
        clearPlaces.addEventListener('click', function () {
            clearPlaceMarkers();
        });


        /* =====================================================
           ESCAPE HTML
        ====================================================== */

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


        function escapeHtml(value) {

            if (value === null || value === undefined) {
                return '';
            }

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