<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Jelajah Wisata Indonesia
    </title>


    <!-- =====================================================
         LEAFLET CSS
    ====================================================== -->

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

            font-family:
                Arial,
                Helvetica,
                sans-serif;

        }


        body {
            background: #000;
        }


        /* =====================================================
           OPENING
        ====================================================== */

        #opening {

            position: fixed;

            inset: 0;

            z-index: 99999;

            background: #000;

            overflow: hidden;

            transition:
                opacity 1s ease,
                visibility 1s ease;

        }


        #opening.hide-opening {

            opacity: 0;

            visibility: hidden;

            pointer-events: none;

        }


        #openingVideo {

            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            object-fit: cover;

        }


        .opening-overlay {

            position: absolute;

            inset: 0;

            background:
                linear-gradient(
                    to bottom,
                    rgba(0,0,0,.35),
                    rgba(0,0,0,.15),
                    rgba(0,0,0,.75)
                );

        }


        .opening-content {

            position: absolute;

            left: 50%;
            bottom: 15%;

            transform:
                translateX(-50%);

            width: 90%;

            text-align: center;

            color: white;

            z-index: 2;

            animation:
                openingText 1.8s ease;

        }


        .opening-small {

            font-size: 15px;

            letter-spacing: 6px;

            margin-bottom: 15px;

        }


        .opening-content h1 {

            font-size:
                clamp(40px, 7vw, 90px);

            font-weight: 800;

            letter-spacing: 5px;

        }


        .opening-content h1 span {

            display: block;

        }


        .opening-line {

            width: 100px;

            height: 3px;

            background: white;

            margin: 25px auto;

        }


        .opening-content p {

            font-size: 16px;

            letter-spacing: 1px;

        }


        @keyframes openingText {

            from {

                opacity: 0;

                transform:
                    translate(
                        -50%,
                        40px
                    );

            }

            to {

                opacity: 1;

                transform:
                    translate(
                        -50%,
                        0
                    );

            }

        }


        /* =====================================================
           HEADER
        ====================================================== */

        .header {

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            z-index: 1000;

            padding: 20px 30px;

            background:
                linear-gradient(
                    to bottom,
                    rgba(0,0,0,.7),
                    transparent
                );

            color: white;

            pointer-events: none;

        }


        .header h1 {

            font-size: 24px;

            text-shadow:
                0 2px 6px rgba(0,0,0,.8);

        }


        /* =====================================================
           MAP
        ====================================================== */

        #map {

            width: 100%;

            height: 100vh;

        }


        /* =====================================================
           SEARCH BUTTON
        ====================================================== */

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

            box-shadow:
                0 4px 15px
                rgba(0,0,0,.3);

        }


        #searchToggle:hover {

            transform: scale(1.08);

        }


        /* =====================================================
           SEARCH PANEL
        ====================================================== */

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

            box-shadow:
                0 10px 35px
                rgba(0,0,0,.35);

            overflow: hidden;

        }


        .search-header {

            display: flex;

            align-items: center;

            justify-content:
                space-between;

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


        /* =====================================================
           SEARCH MODE
        ====================================================== */

        .search-mode {

            padding: 12px 16px;

            background: #f4f4f4;

            font-size: 13px;

            color: #555;

        }


        .search-mode strong {

            color: #111;

        }


        /* =====================================================
           BACK
        ====================================================== */

        #backToProvince {

            display: none;

            width:
                calc(100% - 30px);

            margin:
                12px 15px 0;

            padding: 10px;

            border: none;

            border-radius: 8px;

            background: #222;

            color: white;

            cursor: pointer;

        }


        /* =====================================================
           SEARCH INPUT
        ====================================================== */

        .search-input-wrapper {

            padding: 15px;

        }


        #searchInput {

            width: 100%;

            padding: 13px 15px;

            border:
                1px solid #ddd;

            border-radius: 9px;

            outline: none;

            font-size: 14px;

        }


        /* =====================================================
           SEARCH RESULT
        ====================================================== */

        #searchResults {

            max-height: 45vh;

            overflow-y: auto;

        }


        .search-result {

            padding: 13px 16px;

            border-top:
                1px solid #eee;

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


        /* =====================================================
           LOCATION INFO
        ====================================================== */

        #locationInfo {

            position: absolute;

            left: 20px;
            bottom: 25px;

            z-index: 1500;

            display: none;

            max-width: 430px;

            background:
                rgba(255,255,255,.97);

            padding: 15px 18px;

            border-radius: 12px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,.25);

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


        /* =====================================================
           CATEGORY BUTTON
        ====================================================== */

        #categoryOptions {

            display: none;

            gap: 8px;

            flex-wrap: wrap;

        }


        .category-btn {

            border: none;

            padding:
                10px 14px;

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

            transform:
                translateY(-2px);

            box-shadow:
                0 4px 10px
                rgba(0,0,0,.2);

        }


        /* =====================================================
           CLEAR PLACES
        ====================================================== */

        #clearPlaces {

            display: none;

            margin-top: 8px;

            padding:
                7px 10px;

            border: none;

            border-radius: 6px;

            background: #eee;

            color: #444;

            cursor: pointer;

            font-size: 11px;

        }


        /* =====================================================
           MARKER WISATA
        ====================================================== */

        .tourism-marker {

            width: 34px;
            height: 34px;

            border-radius:
                50% 50% 50% 0;

            background: #2e7d32;

            transform:
                rotate(-45deg);

            border:
                3px solid white;

            box-shadow:
                0 3px 10px
                rgba(0,0,0,.4);

            position: relative;

        }


        .tourism-marker::after {

            content: "★";

            position: absolute;

            color: white;

            font-size: 15px;

            top: 5px;
            left: 7px;

            transform:
                rotate(45deg);

        }


        /* =====================================================
           MARKER KULINER
        ====================================================== */

        .food-marker {

            width: 34px;
            height: 34px;

            border-radius:
                50% 50% 50% 0;

            background: #e65100;

            transform:
                rotate(-45deg);

            border:
                3px solid white;

            box-shadow:
                0 3px 10px
                rgba(0,0,0,.4);

            position: relative;

        }


        .food-marker::after {

            content: "🍜";

            position: absolute;

            font-size: 14px;

            top: 5px;
            left: 5px;

            transform:
                rotate(45deg);

        }


        /* =====================================================
           PLACE POPUP
        ====================================================== */

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


        /* =====================================================
           LOADING
        ====================================================== */

        #placesLoading {

            display: none;

            margin-top: 8px;

            font-size: 12px;

            color: #777;

        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 600px) {

            .header {

                padding:
                    15px 18px;

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


            .opening-content h1 {

                font-size: 42px;

            }

        }

    </style>

</head>


<body>


    <!-- =====================================================
         OPENING SCREEN
    ====================================================== -->

    <div id="opening">

        <video
            id="openingVideo"
            autoplay
            muted
            playsinline>
        </video>

        <div class="opening-overlay"></div>

        <div class="opening-content">

            <div class="opening-small">

                EXPLORE THE BEAUTY OF

            </div>

            <h1>

                WELCOME TO

                <span>
                    INDONESIA
                </span>

            </h1>

            <div class="opening-line"></div>

            <p>

                Discover the beauty,
                culture and diversity
                of Indonesia

            </p>

        </div>

    </div>


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="header">

        <h1>
            🌍 Jelajah Wisata Indonesia
        </h1>

    </div>


    <!-- =====================================================
         MAP
    ====================================================== -->

    <div id="map"></div>


    <!-- =====================================================
         SEARCH BUTTON
    ====================================================== -->

    <button id="searchToggle">
        🔍
    </button>


    <!-- =====================================================
         SEARCH PANEL
    ====================================================== -->

    <div id="searchPanel">

        <div class="search-header">

            <h3 id="searchTitle">
                🔍 Cari Provinsi
            </h3>

            <button id="closeSearch">
                ×
            </button>

        </div>


        <div class="search-mode">

            Mode:

            <strong id="searchMode">
                Provinsi Indonesia
            </strong>

        </div>


        <button id="backToProvince">

            ← Kembali ke Daftar Provinsi

        </button>


        <div class="search-input-wrapper">

            <input
                type="text"
                id="searchInput"
                placeholder="Cari provinsi..."
                autocomplete="off"
            >

        </div>


        <div id="searchResults"></div>

    </div>


    <!-- =====================================================
         LOCATION INFO
    ====================================================== -->

    <div id="locationInfo">

        <strong id="locationName"></strong>

        <span id="locationAddress"></span>


        <!-- ===============================================
             CATEGORY
        ================================================ -->

        <div id="categoryOptions">

            <button
                type="button"
                class="category-btn wisata"
                id="wisataButton"
            >

                🏞️ Wisata

            </button>


            <button
                type="button"
                class="category-btn kuliner"
                id="kulinerButton"
            >

                🍜 Kuliner

            </button>

        </div>


        <!-- =====================================================
             SEARCH TEMPAT WISATA / KULINER
        ====================================================== -->

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
                placeholder="🔎 Cari tempat wisata atau kuliner..."
                autocomplete="off"
                style="
                    width:100%;
                    box-sizing:border-box;
                    padding:11px 14px;
                    border:1px solid #ddd;
                    border-radius:10px;
                    outline:none;
                    font-size:14px;
                    margin-bottom:8px;
                "
            >

            <div
                id="placeSearchResults"
                style="
                    max-height:260px;
                    overflow-y:auto;
                "
            ></div>

        </div>


        <div id="placesLoading">

            ⏳ Mencari tempat...

        </div>


        <button
            type="button"
            id="clearPlaces"
        >

            ✕ Hapus marker tempat

        </button>

    </div>


    <!-- =====================================================
         LEAFLET
    ====================================================== -->

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>


    <script>

        /* =====================================================
           DATA LARAVEL
        ====================================================== */

        const provinces =
            @json($provinces);

        const cities =
            @json($cities);


        /* =====================================================
           OPENING VIDEO
        ====================================================== */

        const indonesiaVideos = [

            '/videos/indonesia-1.mp4',
            '/videos/indonesia-2.mp4',
            '/videos/indonesia-3.mp4',
            '/videos/indonesia-4.mp4',
            '/videos/indonesia-5.mp4'

        ];


        let currentVideo = 0;


        const opening =
            document.getElementById(
                'opening'
            );


        const openingVideo =
            document.getElementById(
                'openingVideo'
            );


        function playNextVideo() {

            if (
                currentVideo >=
                indonesiaVideos.length
            ) {

                currentVideo = 0;

            }


            openingVideo.src =
                indonesiaVideos[
                    currentVideo
                ];


            openingVideo.load();


            openingVideo
                .play()
                .catch(() => {});


            currentVideo++;

        }


        openingVideo.addEventListener(
            'ended',
            playNextVideo
        );


        playNextVideo();


        setTimeout(
            function () {

                opening.classList.add(
                    'hide-opening'
                );

            },
            10000
        );


        /* =====================================================
           MAP
        ====================================================== */

        const map =
            L.map(
                'map',
                {

                    zoomControl: true,

                    attributionControl: true,

                    zoomAnimation: true,

                    fadeAnimation: true,

                    markerZoomAnimation: true,

                    zoomSnap: 1,

                    zoomDelta: 1,

                    wheelPxPerZoomLevel: 80

                }
            );


        /* =====================================================
           OSM
        ====================================================== */

        const osm =
            L.tileLayer(

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


        map.setView(

            [
                -2.5,
                118
            ],

            5

        );


        /* =====================================================
           SEARCH ELEMENT
        ====================================================== */

        const searchToggle =
            document.getElementById(
                'searchToggle'
            );


        const searchPanel =
            document.getElementById(
                'searchPanel'
            );


        const closeSearch =
            document.getElementById(
                'closeSearch'
            );


        const searchInput =
            document.getElementById(
                'searchInput'
            );


        const searchResults =
            document.getElementById(
                'searchResults'
            );


        const searchTitle =
            document.getElementById(
                'searchTitle'
            );


        const searchMode =
            document.getElementById(
                'searchMode'
            );


        const backToProvince =
            document.getElementById(
                'backToProvince'
            );


        /* =====================================================
           LOCATION ELEMENT
        ====================================================== */

        const locationInfo =
            document.getElementById(
                'locationInfo'
            );


        const locationName =
            document.getElementById(
                'locationName'
            );


        const locationAddress =
            document.getElementById(
                'locationAddress'
            );


        /* =====================================================
           CATEGORY ELEMENT
        ====================================================== */

        const categoryOptions =
            document.getElementById(
                'categoryOptions'
            );


        const wisataButton =
            document.getElementById(
                'wisataButton'
            );


        const kulinerButton =
            document.getElementById(
                'kulinerButton'
            );


        const placesLoading =
            document.getElementById(
                'placesLoading'
            );


        const clearPlaces =
            document.getElementById(
                'clearPlaces'
            );


        const placeSearchPanel =
            document.getElementById(
                'placeSearchPanel'
            );


        const placeSearchInput =
            document.getElementById(
                'placeSearchInput'
            );


        const placeSearchResults =
            document.getElementById(
                'placeSearchResults'
            );


        /* =====================================================
           STATE
        ====================================================== */

        let currentProvince = null;

        let currentCity = null;

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

                iconSize:
                    [34, 34],

                iconAnchor:
                    [17, 34],

                popupAnchor:
                    [0, -34]

            });

        }


        /* =====================================================
           ICON KOTA
        ====================================================== */

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

                iconSize:
                    [30, 30],

                iconAnchor:
                    [15, 30],

                popupAnchor:
                    [0, -30]

            });

        }


        /* =====================================================
           ICON WISATA
        ====================================================== */

        function createTourismIcon() {

            return L.divIcon({

                className: '',

                html:
                    '<div class="tourism-marker"></div>',

                iconSize:
                    [34, 34],

                iconAnchor:
                    [17, 34],

                popupAnchor:
                    [0, -34]

            });

        }


        /* =====================================================
           ICON KULINER
        ====================================================== */

        function createFoodIcon() {

            return L.divIcon({

                className: '',

                html:
                    '<div class="food-marker"></div>',

                iconSize:
                    [34, 34],

                iconAnchor:
                    [17, 34],

                popupAnchor:
                    [0, -34]

            });

        }


        /* =====================================================
           SEARCH OPEN
        ====================================================== */

        searchToggle.addEventListener(
            'click',
            function () {

                searchPanel.style.display =
                    'block';

                searchToggle.style.display =
                    'none';

                searchInput.focus();

                renderProvinceResults();

            }
        );


        /* =====================================================
           SEARCH CLOSE
        ====================================================== */

        closeSearch.addEventListener(
            'click',
            function () {

                searchPanel.style.display =
                    'none';

                searchToggle.style.display =
                    'block';

            }
        );


        /* =====================================================
           ESC
        ====================================================== */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                ) {

                    searchPanel.style.display =
                        'none';

                    searchToggle.style.display =
                        'block';

                }

            }
        );


        /* =====================================================
           PROVINSI
        ====================================================== */

        function renderProvinceResults(
            keyword = ''
        ) {

            searchTitle.textContent =
                '🔍 Cari Provinsi';

            searchMode.textContent =
                'Provinsi Indonesia';

            searchInput.placeholder =
                'Cari provinsi...';

            backToProvince.style.display =
                'none';


            const search =
                keyword
                    .toLowerCase()
                    .trim();


            const filtered =
                provinces.filter(
                    province =>

                        province.name
                            .toLowerCase()
                            .includes(search)

                );


            searchResults.innerHTML =
                '';


            if (
                filtered.length === 0
            ) {

                searchResults.innerHTML = `

                    <div class="no-result">

                        Provinsi tidak ditemukan.

                    </div>

                `;

                return;

            }


            filtered.forEach(
                province => {

                    const item =
                        document.createElement(
                            'div'
                        );


                    item.className =
                        'search-result';


                    item.innerHTML = `

                        <div class="result-name">

                            📍 ${province.name}

                        </div>

                        <div class="result-info">

                            Provinsi Indonesia

                        </div>

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            selectProvince(
                                province
                            );

                        }
                    );


                    searchResults.appendChild(
                        item
                    );

                }
            );

        }


        /* =====================================================
           KOTA
        ====================================================== */

        function renderCityResults(
            keyword = ''
        ) {

            if (
                !currentProvince
            ) {

                return;

            }


            searchTitle.textContent =
                '🔍 Cari Kota / Kabupaten';


            searchMode.textContent =
                currentProvince.name;


            searchInput.placeholder =
                'Cari kota atau kabupaten...';


            backToProvince.style.display =
                'block';


            const search =
                keyword
                    .toLowerCase()
                    .trim();


            const filtered =
                cities.filter(
                    city => {

                        return (

                            String(
                                city.province_id
                            ) ===
                            String(
                                currentProvince.id
                            )

                            &&

                            city.name
                                .toLowerCase()
                                .includes(search)

                        );

                    }
                );


            searchResults.innerHTML =
                '';


            if (
                filtered.length === 0
            ) {

                searchResults.innerHTML = `

                    <div class="no-result">

                        Kota / Kabupaten tidak ditemukan.

                    </div>

                `;

                return;

            }


            filtered.forEach(
                city => {

                    const item =
                        document.createElement(
                            'div'
                        );


                    item.className =
                        'search-result';


                    item.innerHTML = `

                        <div class="result-name">

                            📍 ${city.name}

                        </div>

                        <div class="result-info">

                            ${city.type}
                            •
                            ${currentProvince.name}

                        </div>

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            selectCity(
                                city
                            );

                        }
                    );


                    searchResults.appendChild(
                        item
                    );

                }
            );

        }


        /* =====================================================
           PILIH PROVINSI
        ====================================================== */

        async function selectProvince(
            province
        ) {

            currentProvince = province;
            currentCity = null;
            clearPlaceMarkers();

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

            // Utamakan koordinat OpenStreetMap melalui Laravel/Nominatim.
            await searchProvinceOpenStreetMap(province);
        }


        /* =====================================================
           CARI PROVINSI OSM
        ====================================================== */

        async function searchProvinceOpenStreetMap(
            province
        ) {

            try {
                const url =
                    '/api/geocode?' +
                    new URLSearchParams({
                        name: province.name,
                        type: 'province'
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

                // Fallback jika server/Nominatim sedang gagal.
                if (province.latitude && province.longitude) {
                    showProvinceOnMap(
                        parseFloat(province.latitude),
                        parseFloat(province.longitude),
                        province.name,
                        province.name + ' (koordinat database)'
                    );
                    return;
                }

                alert(
                    'Koordinat provinsi tidak ditemukan dari OpenStreetMap.'
                );
            }
        }


        /* =====================================================
           TAMPILKAN PROVINSI
        ====================================================== */

        function showProvinceOnMap(

            latitude,
            longitude,
            name,
            address

        ) {

            currentProvinceMarker =
                L.marker(

                    [
                        latitude,
                        longitude
                    ],

                    {
                        icon:
                            createProvinceIcon()
                    }

                ).addTo(map);


            currentProvinceMarker
                .bindPopup(`

                    <div class="place-popup-title">
                        ${name}
                    </div>

                    <div class="place-popup-type">
                        Provinsi Indonesia
                    </div>

                `);


            currentProvinceMarker
                .openPopup();


            map.flyTo(

                [
                    latitude,
                    longitude
                ],

                8,

                {

                    animate: true,

                    duration: 1.8

                }

            );


            locationInfo.style.display =
                'block';


            locationName.textContent =
                name;


            locationAddress.textContent =
                address;


            setTimeout(
                function () {

                    searchInput.value =
                        '';

                    renderCityResults();

                },
                700
            );

        }


        /* =====================================================
           PILIH KOTA
        ====================================================== */

        async function selectCity(
            city
        ) {

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

            // Utamakan koordinat OpenStreetMap melalui Laravel/Nominatim.
            await searchCityOpenStreetMap(city);
        }


        /* =====================================================
           CARI KOTA OSM
        ====================================================== */

        async function searchCityOpenStreetMap(
            city
        ) {

            try {
                const url =
                    '/api/geocode?' +
                    new URLSearchParams({
                        name: city.name,
                        type: 'city',
                        province: currentProvince.name,
                        city_type: city.type
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

                const latitude = parseFloat(data.latitude);
                const longitude = parseFloat(data.longitude);

                // Simpan koordinat OSM agar /api/places memakai lokasi yang sama.
                currentCity.latitude = latitude;
                currentCity.longitude = longitude;

                showCityOnMap(
                    latitude,
                    longitude,
                    city.name,
                    data.display_name
                );

            } catch (error) {
                console.error('Geocoding kota/kabupaten:', error);

                // Fallback ke koordinat database jika OSM tidak dapat diakses.
                if (city.latitude && city.longitude) {
                    currentCity.latitude = parseFloat(city.latitude);
                    currentCity.longitude = parseFloat(city.longitude);

                    showCityOnMap(
                        currentCity.latitude,
                        currentCity.longitude,
                        city.name,
                        `${city.type} • ${currentProvince.name} (koordinat database)`
                    );
                    return;
                }

                alert(
                    'Koordinat kota/kabupaten tidak ditemukan dari OpenStreetMap.'
                );
            }
        }


        /* =====================================================
           TAMPILKAN KOTA
        ====================================================== */

        function showCityOnMap(

            latitude,
            longitude,
            name,
            address

        ) {

            currentCityMarker =
                L.marker(

                    [
                        latitude,
                        longitude
                    ],

                    {
                        icon:
                            createCityIcon()
                    }

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


            currentCityMarker
                .openPopup();


            map.flyTo(

                [
                    latitude,
                    longitude
                ],

                13,

                {

                    animate: true,

                    duration: 1.8

                }

            );


            locationInfo.style.display =
                'block';


            locationName.textContent =
                name;


            locationAddress.textContent =
                address;


            /*
            =====================================================
            INI YANG BARU

            Setelah kota berhasil dipilih,
            tombol Wisata dan Kuliner muncul.
            =====================================================
            */

            categoryOptions.style.display =
                'flex';

        }


        /* =====================================================
           INPUT SEARCH
        ====================================================== */

        searchInput.addEventListener(
            'input',
            function () {

                if (
                    currentProvince
                ) {

                    renderCityResults(
                        this.value
                    );

                }
                else {

                    renderProvinceResults(
                        this.value
                    );

                }

            }
        );


        /* =====================================================
           KEMBALI KE PROVINSI
        ====================================================== */

        backToProvince.addEventListener(
            'click',
            function () {

                currentProvince =
                    null;

                currentCity =
                    null;


                clearPlaceMarkers();


                if (
                    currentProvinceMarker
                ) {

                    map.removeLayer(
                        currentProvinceMarker
                    );

                    currentProvinceMarker =
                        null;

                }


                if (
                    currentCityMarker
                ) {

                    map.removeLayer(
                        currentCityMarker
                    );

                    currentCityMarker =
                        null;

                }


                locationInfo.style.display =
                    'none';


                categoryOptions.style.display =
                    'none';


                placeSearchPanel.style.display =
                    'none';


                placeSearchInput.value =
                    '';


                placeSearchResults.innerHTML =
                    '';


                loadedPlaces = [];

                currentPlaceCategory = '';


                placesLoading.style.display =
                    'none';


                clearPlaces.style.display =
                    'none';


                map.flyTo(

                    [
                        -2.5,
                        118
                    ],

                    5,

                    {

                        animate: true,

                        duration: 1.5

                    }

                );


                searchInput.value =
                    '';


                renderProvinceResults();

            }
        );


        /* =====================================================
           WISATA
        ====================================================== */

        wisataButton.addEventListener(
            'click',
            function () {

                if (!currentCity) {
                    return;
                }

                loadPlaces('wisata');
            }
        );


        /* =====================================================
           KULINER
        ====================================================== */

        kulinerButton.addEventListener(
            'click',
            function () {

                if (!currentCity) {
                    return;
                }

                loadPlaces('kuliner');
            }
        );


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

                    const position =
                        currentCityMarker.getLatLng();

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

                renderPlaceSearchResults(
                    loadedPlaces,
                    category
                );

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
                            padding:12px;
                            color:#666;
                            background:#f5f5f5;
                            border-radius:8px;
                            font-size:13px;
                        "
                    >
                        ${
                            category === 'wisata'
                                ? 'Tidak ditemukan tempat wisata.'
                                : 'Tidak ditemukan tempat kuliner.'
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

                    return `
                        <div
                            class="place-search-item"
                            data-index="${index}"
                            style="
                                padding:11px 12px;
                                margin-bottom:6px;
                                background:white;
                                border:1px solid #e0e0e0;
                                border-radius:9px;
                                cursor:pointer;
                                transition:.2s;
                            "
                        >
                            <div
                                style="
                                    font-weight:600;
                                    font-size:14px;
                                    color:#222;
                                "
                            >
                                ${category === 'wisata' ? '🏞️' : '🍜'}
                                ${escapeHtml(place.name)}
                            </div>

                            <div
                                style="
                                    margin-top:4px;
                                    font-size:12px;
                                    color:#777;
                                "
                            >
                                ${escapeHtml(place.type || '')}
                                ${distance ? ` • ${distance}` : ''}
                            </div>
                        </div>
                    `;
                }).join('');

            const items =
                placeSearchResults.querySelectorAll(
                    '.place-search-item'
                );

            items.forEach(item => {

                item.addEventListener(
                    'mouseenter',
                    function () {
                        this.style.background = '#f5f5f5';
                    }
                );

                item.addEventListener(
                    'mouseleave',
                    function () {
                        this.style.background = 'white';
                    }
                );

                item.addEventListener(
                    'click',
                    function () {

                        const index =
                            parseInt(this.dataset.index, 10);

                        const place =
                            places[index];

                        if (place) {
                            showSelectedPlace(
                                place,
                                category
                            );
                        }
                    }
                );
            });
        }


        /* =====================================================
           SEARCH INPUT TEMPAT
        ====================================================== */

        placeSearchInput.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value.trim().toLowerCase();

                if (keyword === '') {

                    renderPlaceSearchResults(
                        loadedPlaces,
                        currentPlaceCategory
                    );

                    return;
                }

                const filtered =
                    loadedPlaces.filter(place => {

                        const name =
                            (place.name || '').toLowerCase();

                        const type =
                            (place.type || '').toLowerCase();

                        return (
                            name.includes(keyword) ||
                            type.includes(keyword)
                        );
                    });

                renderPlaceSearchResults(
                    filtered,
                    currentPlaceCategory
                );
            }
        );


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

            const latitude =
                parseFloat(place.latitude);

            const longitude =
                parseFloat(place.longitude);

            const marker =
                L.marker(
                    [latitude, longitude],
                    { icon: icon }
                );

            const categoryName =
                category === 'wisata'
                    ? '🏞️ Wisata'
                    : '🍜 Kuliner';

            marker.bindPopup(`
                <div class="place-popup-title">
                    ${escapeHtml(place.name)}
                </div>

                <div class="place-popup-type">
                    ${categoryName}
                    •
                    ${escapeHtml(place.type || '')}
                </div>

                ${
                    place.address
                        ? `
                            <div class="place-popup-address">
                                ${escapeHtml(place.address)}
                            </div>
                        `
                        : ''
                }
            `);

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


        /* =====================================================
           BUTTON HAPUS MARKER
        ====================================================== */

        clearPlaces.addEventListener(
            'click',
            function () {
                clearPlaceMarkers();
            }
        );


        /* =====================================================
           ESCAPE HTML
        ====================================================== */

        function escapeHtml(
            value
        ) {

            if (
                value === null ||
                value === undefined
            ) {

                return '';

            }


            return String(value)

                .replace(
                    /&/g,
                    '&amp;'
                )

                .replace(
                    /</g,
                    '&lt;'
                )

                .replace(
                    />/g,
                    '&gt;'
                )

                .replace(
                    /"/g,
                    '&quot;'
                )

                .replace(
                    /'/g,
                    '&#039;'
                );

        }


        /* =====================================================
           INITIAL
        ====================================================== */

        renderProvinceResults();

    </script>

</body>

</html>