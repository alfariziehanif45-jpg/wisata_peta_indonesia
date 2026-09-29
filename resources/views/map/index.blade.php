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
}

    </style>

</head>


<body>

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
                🔍 Cari Negara ASEAN
            </h3>

            <button id="closeSearch">
                ×
            </button>

        </div>


        <div class="search-mode">

            Mode:

            <strong id="searchMode">
                Negara ASEAN
            </strong>

        </div>


        <button id="backToProvince" style="display:none;">

            ← Kembali

        </button>


        <div class="search-input-wrapper">

            <input
                type="text"
                id="searchInput"
                placeholder="Cari negara ASEAN..."
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

        // 11 negara ASEAN. Provinsi dan kota yang tersedia di database
        // saat ini tetap difokuskan untuk Indonesia.
        const aseanCountries = [
            { name: 'Brunei Darussalam', code: 'bn' },
            { name: 'Cambodia', code: 'kh' },
            { name: 'Indonesia', code: 'id' },
            { name: 'Laos', code: 'la' },
            { name: 'Malaysia', code: 'my' },
            { name: 'Myanmar', code: 'mm' },
            { name: 'Philippines', code: 'ph' },
            { name: 'Singapore', code: 'sg' },
            { name: 'Thailand', code: 'th' },
            { name: 'Timor-Leste', code: 'tl' },
            { name: 'Vietnam', code: 'vn' }
        ];

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

        let currentCountry = null;

        let currentProvince = null;

        let currentCity = null;

        let currentCountryMarker = null;

        let currentProvinceMarker = null;

        let currentCityMarker = null;

        let placeMarkers = [];

        let selectedPlaceMarker = null;

        let loadedPlaces = [];

        let currentPlaceCategory = '';

        let remoteProvinces = [];
        let remoteCities = [];
        let remoteProvinceCache = {};
        let remoteCityCache = {};

        /*
         * Fallback lokal: daftar awal ditampilkan TANPA menunggu API.
         * API CountriesNow tetap berjalan di belakang layar untuk melengkapi/
         * memperbarui data. Jadi koneksi lambat tidak membuat panel kosong.
         */
        const instantRemoteRegions = {
            bn: [
                'Belait', 'Brunei-Muara', 'Temburong', 'Tutong'
            ],
            kh: [
                'Banteay Meanchey', 'Battambang', 'Kampong Cham', 'Kampong Chhnang',
                'Kampong Speu', 'Kampong Thom', 'Kampot', 'Kandal', 'Kep', 'Koh Kong',
                'Kratie', 'Mondulkiri', 'Oddar Meanchey', 'Pailin', 'Phnom Penh',
                'Preah Sihanouk', 'Preah Vihear', 'Pursat', 'Prey Veng', 'Ratanakiri',
                'Siem Reap', 'Stung Treng', 'Svay Rieng', 'Takeo', 'Tbong Khmum'
            ],
            la: [
                'Attapeu', 'Bokeo', 'Bolikhamsai', 'Champasak', 'Houaphanh', 'Khammouane',
                'Luang Namtha', 'Luang Prabang', 'Oudomxay', 'Phongsaly', 'Salavan',
                'Savannakhet', 'Sekong', 'Vientiane Capital', 'Vientiane Province',
                'Xaignabouli', 'Xaisomboun', 'Xieng Khouang'
            ],
            my: [
                'Johor', 'Kedah', 'Kelantan', 'Malacca', 'Negeri Sembilan', 'Pahang',
                'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor',
                'Kuala Lumpur', 'Labuan', 'Putrajaya'
            ],
            mm: [
                'Ayeyarwady', 'Bago', 'Chin', 'Kachin', 'Kayah', 'Kayin', 'Magway',
                'Mandalay', 'Mon', 'Naypyidaw', 'Rakhine', 'Sagaing', 'Shan', 'Tanintharyi',
                'Yangon'
            ],
            ph: [
                'Ilocos Region', 'Cagayan Valley', 'Central Luzon', 'CALABARZON',
                'MIMAROPA', 'Bicol Region', 'Western Visayas', 'Central Visayas',
                'Eastern Visayas', 'Zamboanga Peninsula', 'Northern Mindanao',
                'Davao Region', 'SOCCSKSARGEN', 'Caraga', 'Bangsamoro Autonomous Region in Muslim Mindanao',
                'Cordillera Administrative Region', 'National Capital Region', 'Central Luzon'
            ],
            sg: [
                'Singapore'
            ],
            th: [
                'Amnat Charoen', 'Ang Thong', 'Bangkok', 'Bueng Kan', 'Buri Ram', 'Chachoengsao',
                'Chai Nat', 'Chaiyaphum', 'Chanthaburi', 'Chiang Mai', 'Chiang Rai', 'Chon Buri',
                'Chumphon', 'Kalasin', 'Kamphaeng Phet', 'Kanchanaburi', 'Khon Kaen', 'Krabi',
                'Lampang', 'Lamphun', 'Loei', 'Lopburi', 'Mae Hong Son', 'Maha Sarakham',
                'Mukdahan', 'Nakhon Nayok', 'Nakhon Pathom', 'Nakhon Phanom', 'Nakhon Ratchasima',
                'Nakhon Sawan', 'Nakhon Si Thammarat', 'Nan', 'Narathiwat', 'Nong Bua Lamphu',
                'Nong Khai', 'Nonthaburi', 'Pathum Thani', 'Pattani', 'Phang Nga', 'Phatthalung',
                'Phayao', 'Phetchabun', 'Phetchaburi', 'Phichit', 'Phitsanulok', 'Phra Nakhon Si Ayutthaya',
                'Phrae', 'Phuket', 'Prachin Buri', 'Prachuap Khiri Khan', 'Ranong', 'Ratchaburi',
                'Rayong', 'Roi Et', 'Sa Kaeo', 'Sakon Nakhon', 'Samut Prakan', 'Samut Sakhon',
                'Samut Songkhram', 'Saraburi', 'Satun', 'Sing Buri', 'Sisaket', 'Songkhla',
                'Sukhothai', 'Suphan Buri', 'Surat Thani', 'Surin', 'Tak', 'Trang', 'Trat',
                'Ubon Ratchathani', 'Udon Thani', 'Uthai Thani', 'Uttaradit', 'Yala', 'Yasothon'
            ],
            tl: [
                'Aileu', 'Ainaro', 'Baucau', 'Bobonaro', 'Cova Lima', 'Dili', 'Ermera',
                'Lautem', 'Liquica', 'Manatuto', 'Manufahi', 'Oecusse', 'Viqueque'
            ],
            vn: [
                'An Giang', 'Bắc Ninh', 'Cà Mau', 'Cao Bằng', 'Cần Thơ', 'Đà Nẵng',
                'Đắk Lắk', 'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Nội',
                'Hà Tĩnh', 'Hải Phòng', 'Hưng Yên', 'Huế', 'Khánh Hòa', 'Lai Châu',
                'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Nghệ An', 'Ninh Bình', 'Phú Thọ',
                'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị', 'Sơn La', 'Tây Ninh', 'Thái Nguyên',
                'Thanh Hóa', 'Thành phố Hồ Chí Minh', 'Tuyên Quang', 'Vĩnh Long'
            ]
        };

        const instantRemoteCities = {
            'vn|Hà Nội': ['Hà Nội'],
            'vn|Thành phố Hồ Chí Minh': ['Thành phố Hồ Chí Minh', 'Thủ Đức'],
            'vn|Đà Nẵng': ['Đà Nẵng'],
            'vn|Hải Phòng': ['Hải Phòng'],
            'vn|Cần Thơ': ['Cần Thơ'],
            'vn|Huế': ['Huế'],
            'my|Johor': ['Johor Bahru', 'Batu Pahat', 'Muar', 'Kluang'],
            'my|Selangor': ['Shah Alam', 'Petaling Jaya', 'Klang', 'Subang Jaya'],
            'my|Penang': ['George Town', 'Butterworth'],
            'my|Sarawak': ['Kuching', 'Miri', 'Sibu'],
            'my|Sabah': ['Kota Kinabalu', 'Sandakan', 'Tawau'],
            'th|Bangkok': ['Bangkok'],
            'kh|Siem Reap': ['Siem Reap'],
            'kh|Phnom Penh': ['Phnom Penh'],
            'la|Vientiane Capital': ['Vientiane'],
            'mm|Yangon': ['Yangon'],
            'mm|Mandalay': ['Mandalay'],
            'tl|Dili': ['Dili'],
            'bn|Brunei-Muara': ['Bandar Seri Begawan'],
            'sg|Singapore': ['Singapore']
        };

        function buildInstantRegions(countryCode) {
            return (instantRemoteRegions[countryCode] || []).map((name, index) => ({
                id: 'instant-' + countryCode + '-' + index,
                name,
                type: 'Wilayah',
                latitude: null,
                longitude: null,
                country_code: countryCode,
                instant: true
            }));
        }

        function buildInstantCities(countryCode, provinceName) {
            const names = instantRemoteCities[
                countryCode + '|' + provinceName
            ] || [];

            return names.map((name, index) => ({
                id: 'instant-city-' + countryCode + '-' + index,
                name,
                type: 'Kota/Kabupaten',
                latitude: null,
                longitude: null,
                country_code: countryCode,
                region_name: provinceName,
                instant: true
            }));
        }
        let remoteCityLoading = {};
        let remoteCitySearchTimer = null;
        let remoteCityRequestId = 0;


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

                renderCountryResults();
        prefetchRemoteProvinces();

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
           NEGARA ASEAN
        ====================================================== */

        function renderCountryResults(keyword = '') {

            searchTitle.textContent =
                '🔍 Cari Negara ASEAN';

            searchMode.textContent =
                'Negara ASEAN';

            searchInput.placeholder =
                'Cari negara ASEAN...';

            backToProvince.style.display =
                'none';

            const search =
                keyword.toLowerCase().trim();

            const filtered =
                aseanCountries.filter(country =>
                    country.name.toLowerCase().includes(search)
                );

            searchResults.innerHTML = '';

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Negara ASEAN tidak ditemukan.
                    </div>
                `;
                return;
            }

            filtered.forEach(country => {
                const item = document.createElement('div');
                item.className = 'search-result';

                item.innerHTML = `
                    <div class="result-name">
                        🌏 ${country.name}
                    </div>
                    <div class="result-info">
                        Negara ASEAN
                    </div>
                `;

                item.addEventListener('click', function () {
                    selectCountry(country);
                });

                searchResults.appendChild(item);
            });
        }


        /* =====================================================
           PROVINSI
        ====================================================== */

        function renderProvinceResults(
            keyword = ''
        ) {

            if (
                currentCountry &&
                currentCountry.code !== 'id'
            ) {
                renderRemoteProvinceResults(keyword);
                return;
            }

            searchTitle.textContent =
                '🔍 Cari Provinsi';

            searchMode.textContent =
                'Indonesia';

            searchInput.placeholder =
                'Cari provinsi...';

            backToProvince.style.display =
                'block';

            backToProvince.textContent =
                '← Kembali ke Negara';

            const search =
                keyword.toLowerCase().trim();

            const filtered =
                provinces.filter(
                    province =>
                        province.name
                            .toLowerCase()
                            .includes(search)
                );

            searchResults.innerHTML = '';

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Provinsi tidak ditemukan.
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
                        ${currentCountry ? currentCountry.name : 'Indonesia'}
                    </div>
                `;

                item.addEventListener('click', function () {
                    selectProvince(province);
                });

                searchResults.appendChild(item);
            });
        }


        /* =====================================================
           PROVINSI / WILAYAH NEGARA LAIN
           Data diambil dari batas administrasi OpenStreetMap.
        ====================================================== */

        async function loadRemoteProvinces(country) {

            searchTitle.textContent =
                '🔍 Cari Wilayah / Provinsi';

            searchMode.textContent =
                country.name;

            searchInput.placeholder =
                'Cari provinsi / wilayah...';

            backToProvince.style.display = 'block';
            backToProvince.textContent = '← Kembali ke Negara';

            const cacheKey = country.code.toLowerCase();

            // Jika data sudah pernah diambil, tampilkan langsung tanpa menunggu server.
            if (remoteProvinceCache[cacheKey]?.length) {
                remoteProvinces = remoteProvinceCache[cacheKey];
                renderRemoteProvinceResults('');
                return remoteProvinces;
            }

            const storageKey = 'jelajah_remote_provinces_' + cacheKey;

            try {
                const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
                if (Array.isArray(saved) && saved.length) {
                    remoteProvinceCache[cacheKey] = saved;
                    remoteProvinces = saved;
                    renderRemoteProvinceResults('');
                    // Refresh di belakang layar, tanpa mengganggu daftar yang sudah tampil.
                    fetchRemoteProvinces(country, cacheKey, storageKey);
                    return saved;
                }
            } catch (error) {
                console.warn('Cache wilayah tidak dapat dibaca:', error);
            }

            // Jangan tampilkan spinner kosong. Gunakan daftar lokal terlebih dahulu.
            const instant = buildInstantRegions(cacheKey);
            if (instant.length) {
                remoteProvinceCache[cacheKey] = instant;
                remoteProvinces = instant;
                renderRemoteProvinceResults('');
            }

            // API tetap dipanggil di belakang layar untuk mengganti data fallback
            // dengan data yang lebih lengkap ketika tersedia.
            fetchRemoteProvinces(country, cacheKey, storageKey);
            return remoteProvinces;
        }


        async function fetchRemoteProvinces(country, cacheKey, storageKey) {

            try {
                const response = await fetch(
                    '/api/regions/' + encodeURIComponent(country.code),
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Daftar wilayah tidak dapat diambil.'
                    );
                }

                remoteProvinces = data.regions || [];
                remoteProvinceCache[cacheKey] = remoteProvinces;

                try {
                    localStorage.setItem(
                        storageKey,
                        JSON.stringify(remoteProvinces)
                    );
                } catch (error) {
                    console.warn('Cache wilayah tidak dapat disimpan:', error);
                }

                // Tampilkan segera setelah data tersedia.
                if (currentCountry && currentCountry.code === country.code && !currentProvince) {
                    renderRemoteProvinceResults('');
                }

                return remoteProvinces;

            } catch (error) {
                console.error('Wilayah negara:', error);

                if (!remoteProvinces.length && currentCountry && currentCountry.code === country.code) {
                    searchResults.innerHTML = `
                        <div class="no-result">
                            ❌ ${escapeHtml(error.message || 'Gagal mengambil wilayah.')}
                            <br><br>
                            Coba buka kembali negara tersebut setelah beberapa saat.
                        </div>
                    `;
                }

                return [];
            }
        }

        function renderRemoteProvinceResults(keyword = '') {

            const search =
                keyword.toLowerCase().trim();

            searchTitle.textContent =
                '🔍 Cari Wilayah / Provinsi';

            searchMode.textContent =
                currentCountry ? currentCountry.name : 'Negara';

            searchInput.placeholder =
                'Cari provinsi / wilayah...';

            backToProvince.style.display = 'block';
            backToProvince.textContent = '← Kembali ke Negara';

            const filtered = remoteProvinces.filter(region =>
                region.name.toLowerCase().includes(search)
            );

            searchResults.innerHTML = '';

            if (filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Wilayah tidak ditemukan.
                    </div>
                `;
                return;
            }

            filtered.forEach(region => {
                const item = document.createElement('div');
                item.className = 'search-result';

                item.innerHTML = `
                    <div class="result-name">
                        📍 ${escapeHtml(region.name)}
                    </div>
                    <div class="result-info">
                        ${escapeHtml(region.type || 'Wilayah')} • ${escapeHtml(currentCountry.name)}
                    </div>
                `;

                item.addEventListener('click', function () {
                    selectRemoteProvince(region);
                });

                searchResults.appendChild(item);
            });
        }


        async function searchRemoteProvinceByName(keyword) {

            try {
                const response = await fetch(
                    '/api/location-search?' +
                    new URLSearchParams({
                        name: keyword,
                        type: 'province',
                        country_code: currentCountry.code,
                        country_name: currentCountry.name
                    }),
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const data = await response.json();
                const results = data.results || [];

                if (!results.length) {
                    return;
                }

                searchResults.innerHTML = '';

                results.forEach(region => {
                    const item = document.createElement('div');
                    item.className = 'search-result';
                    item.innerHTML = `
                        <div class="result-name">
                            📍 ${escapeHtml(region.name)}
                        </div>
                        <div class="result-info">
                            Wilayah • OpenStreetMap
                        </div>
                    `;

                    item.addEventListener('click', function () {
                        selectRemoteProvince(region);
                    });

                    searchResults.appendChild(item);
                });

            } catch (error) {
                console.error('Pencarian wilayah OSM:', error);
            }
        }


        /* =====================================================
           KOTA
        ====================================================== */

        function renderCityResults(
            keyword = ''
        ) {

            if (currentProvince && currentProvince.remote) {
                renderRemoteCityResults(keyword);
                return;
            }

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

            backToProvince.textContent =
                '← Kembali ke Provinsi';


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
           PILIH NEGARA ASEAN
        ====================================================== */

        async function selectCountry(country) {

            currentCountry = country;
            currentProvince = null;
            currentCity = null;
            remoteProvinces = [];
            remoteCities = [];

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
            placeSearchPanel.style.display = 'none';
            placesLoading.style.display = 'none';
            clearPlaces.style.display = 'none';
            locationInfo.style.display = 'none';

            searchInput.value = '';

            if (country.code === 'id') {
                renderProvinceResults();
                await searchCountryOpenStreetMap(country);
                return;
            }

            // Daftar wilayah ditampilkan dari cache jika sudah ada.
            // Jika belum ada, pengambilan data berjalan di belakang layar.
            renderRemoteProvinceResults('');

            loadRemoteProvinces(country);
            searchCountryOpenStreetMap(country);
        }


        /* =====================================================
           CARI NEGARA OSM
        ====================================================== */

        async function searchCountryOpenStreetMap(country) {

            try {
                const url =
                    '/api/geocode?' +
                    new URLSearchParams({
                        name: country.name,
                        type: 'country',
                        country_code: country.code
                    });

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(
                        data.message || 'Negara tidak ditemukan.'
                    );
                }

                showCountryOnMap(
                    parseFloat(data.latitude),
                    parseFloat(data.longitude),
                    country.name,
                    data.display_name
                );

            } catch (error) {
                console.error('Geocoding negara:', error);
                alert(
                    'Koordinat negara tidak ditemukan dari OpenStreetMap.'
                );
            }
        }


        /* =====================================================
           TAMPILKAN NEGARA
        ====================================================== */

        function showCountryOnMap(
            latitude,
            longitude,
            name,
            address
        ) {

            currentCountryMarker = L.marker(
                [latitude, longitude],
                { icon: createProvinceIcon() }
            ).addTo(map);

            currentCountryMarker
                .bindPopup(`
                    <div class="place-popup-title">
                        ${escapeHtml(name)}
                    </div>
                    <div class="place-popup-type">
                        Negara ASEAN
                    </div>
                `);

            currentCountryMarker.openPopup();

            map.flyTo(
                [latitude, longitude],
                name === 'Singapore' ? 11 : 6,
                { animate: true, duration: 1.8 }
            );

            locationInfo.style.display = 'block';
            locationName.textContent = name;
            locationAddress.textContent =
                name === 'Indonesia'
                    ? 'Pilih provinsi untuk melihat kota/kabupaten.'
                    : 'Pilih wilayah/provinsi untuk melihat kota atau kabupaten negara yang dipilih.';

            if (name !== 'Indonesia') {
                backToProvince.style.display = 'block';
                backToProvince.textContent = '← Kembali ke Wilayah';
            }
        }


        /* =====================================================
           PILIH PROVINSI
        ====================================================== */

        async function selectProvince(
            province
        ) {

            currentProvince = province;
            currentCity = null;
            remoteCities = [];
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

            if (province.remote) {
                // Mulai mengambil daftar kota di belakang layar.
                loadRemoteCities(province);

                const latitude = parseFloat(province.latitude);
                const longitude = parseFloat(province.longitude);

                // Fallback lokal tidak memiliki koordinat. Dalam kondisi itu,
                // geocode hanya dijalankan saat wilayah benar-benar dipilih.
                if (Number.isFinite(latitude) && Number.isFinite(longitude)) {
                    showProvinceOnMap(
                        latitude,
                        longitude,
                        province.name,
                        province.display_name ||
                            `${province.name} • ${currentCountry.name}`
                    );
                } else {
                    await searchProvinceOpenStreetMap(province);
                }
                return;
            }

            await searchProvinceOpenStreetMap(province);
        }


        async function selectRemoteProvince(region) {
            selectProvince({
                ...region,
                remote: true,
                display_name:
                    region.display_name ||
                    `${region.name} • ${currentCountry.name}`
            });
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
                        ${currentCountry ? currentCountry.name : 'Indonesia'}
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
           KOTA NEGARA LAIN
        ====================================================== */

        function renderRemoteCityResults(keyword = '') {

            searchTitle.textContent =
                '🔍 Cari Kota / Kabupaten';

            searchMode.textContent =
                currentProvince
                    ? currentProvince.name
                    : (currentCountry ? currentCountry.name : 'Negara');

            searchInput.placeholder =
                'Cari kota atau kabupaten...';

            backToProvince.style.display = 'block';
            backToProvince.textContent = '← Kembali ke Wilayah';

            if (!currentProvince) {
                return;
            }

            const search = keyword.toLowerCase().trim();
            const cacheKey = remoteCityCacheKey(currentProvince);

            // Data belum datang: request sudah dimulai saat provinsi dipilih.
            if (!remoteCities.length && !remoteCityLoading[cacheKey]) {
                loadRemoteCities(currentProvince);
            }

            if (!remoteCities.length) {
                const instant = buildInstantCities(
                    currentCountry.code.toLowerCase(),
                    currentProvince.name
                );

                if (instant.length) {
                    remoteCityCache[cacheKey] = instant;
                    remoteCities = instant;
                }
            }

            // Tidak lagi menahan UI dengan spinner. Jika fallback tersedia,
            // tampilkan sekarang; data API akan menggantinya di belakang layar.
            if (remoteCityLoading[cacheKey] && !remoteCities.length) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Daftar kota akan dilengkapi otomatis.
                        <br><br>
                        🔎 Ketik nama kota untuk mencari langsung.
                    </div>
                `;
                return;
            }

            const filtered = remoteCities.filter(city =>
                city.name.toLowerCase().includes(search)
            );

            searchResults.innerHTML = '';

            if (!filtered.length) {
                searchResults.innerHTML = `
                    <div class="no-result">
                        Kota/kabupaten tidak ditemukan.
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
                        ${escapeHtml(city.type || 'Kota/Kabupaten')} • OpenStreetMap
                    </div>
                `;

                item.addEventListener('click', function () {
                    selectRemoteCity(city);
                });

                searchResults.appendChild(item);
            });
        }


        function remoteCityCacheKey(province) {
            return [
                currentCountry ? currentCountry.code.toLowerCase() : 'xx',
                province.osm_id || province.id || province.name
            ].join('_');
        }


        async function loadRemoteCities(province) {

            if (!currentCountry || !province) {
                return [];
            }

            const cacheKey = remoteCityCacheKey(province);

            if (remoteCityCache[cacheKey]?.length) {
                remoteCities = remoteCityCache[cacheKey];
                return remoteCities;
            }

            if (remoteCityLoading[cacheKey]) {
                return remoteCityLoading[cacheKey];
            }

            const storageKey = 'jelajah_remote_cities_' + cacheKey;

            try {
                const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
                if (Array.isArray(saved) && saved.length) {
                    remoteCityCache[cacheKey] = saved;
                    remoteCities = saved;
                    renderRemoteCityResults(searchInput.value);
                    return saved;
                }
            } catch (error) {
                console.warn('Cache kota tidak dapat dibaca:', error);
            }

            remoteCityLoading[cacheKey] = (async function () {
                try {
                    const response = await fetch(
                        '/api/regions/' + encodeURIComponent(currentCountry.code) + '/cities?' +
                        new URLSearchParams({
                            country_code: currentCountry.code,
                            region_name: province.name
                        }),
                        {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message || 'Daftar kota tidak dapat diambil.'
                        );
                    }

                    const cities = data.cities || [];
                    remoteCityCache[cacheKey] = cities;
                    remoteCities = cities;

                    try {
                        localStorage.setItem(
                            storageKey,
                            JSON.stringify(cities)
                        );
                    } catch (error) {
                        console.warn('Cache kota tidak dapat disimpan:', error);
                    }

                    if (currentProvince && remoteCityCacheKey(currentProvince) === cacheKey) {
                        renderRemoteCityResults(searchInput.value);
                    }

                    return cities;

                } catch (error) {
                    console.error('Daftar kota OSM:', error);

                    if (currentProvince && remoteCityCacheKey(currentProvince) === cacheKey) {
                        searchResults.innerHTML = `
                            <div class="no-result">
                                ❌ Daftar kota belum tersedia. Coba pilih provinsi lagi.
                            </div>
                        `;
                    }

                    return [];
                } finally {
                    delete remoteCityLoading[cacheKey];
                }
            })();

            return remoteCityLoading[cacheKey];
        }

        async function selectRemoteCity(city) {

            currentCity = {
                ...city,
                remote: true,
                latitude: city.latitude !== null && city.latitude !== undefined
                    ? parseFloat(city.latitude)
                    : null,
                longitude: city.longitude !== null && city.longitude !== undefined
                    ? parseFloat(city.longitude)
                    : null
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

            // CountriesNow dipakai untuk membuat daftar kota cepat.
            // Koordinat kota diambil dari OSM hanya saat kota benar-benar dipilih.
            if (
                !Number.isFinite(currentCity.latitude) ||
                !Number.isFinite(currentCity.longitude)
            ) {
                try {
                    const response = await fetch(
                        '/api/geocode?' +
                        new URLSearchParams({
                            name: currentCity.name,
                            type: 'city',
                            province: currentProvince.name,
                            country_code: currentCountry.code,
                            country_name: currentCountry.name
                        }),
                        {
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Koordinat kota tidak ditemukan.');
                    }

                    currentCity.latitude = parseFloat(data.latitude);
                    currentCity.longitude = parseFloat(data.longitude);
                    currentCity.display_name = data.display_name || currentCity.display_name;

                } catch (error) {
                    console.error('Geocoding kota:', error);
                    alert('Koordinat kota tidak ditemukan dari OpenStreetMap.');
                    return;
                }
            }

            showCityOnMap(
                currentCity.latitude,
                currentCity.longitude,
                currentCity.name,
                currentCity.display_name ||
                    `${currentCity.name} • ${currentProvince.name}, ${currentCountry.name}`
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

                if (currentProvince) {
                    renderCityResults(this.value);
                } else if (
                    currentCountry &&
                    currentCountry.code !== 'id'
                ) {
                    renderRemoteProvinceResults(this.value);
                } else if (currentCountry && currentCountry.code === 'id') {
                    renderProvinceResults(this.value);
                } else {
                    renderCountryResults(this.value);
                }
            }
        );


        /* =====================================================
           TOMBOL KEMBALI HIERARKI
        ====================================================== */

        backToProvince.addEventListener(
            'click',
            function () {

                // Jika sedang di kota negara lain -> kembali ke daftar wilayah.
                if (currentCity && currentProvince && currentProvince.remote) {

                    if (currentCityMarker) {
                        map.removeLayer(currentCityMarker);
                        currentCityMarker = null;
                    }

                    clearPlaceMarkers();
                    currentCity = null;
                    categoryOptions.style.display = 'none';
                    placeSearchPanel.style.display = 'none';
                    locationInfo.style.display = 'none';
                    searchInput.value = '';
                    renderRemoteProvinceResults();
                    return;
                }

                // Jika sedang di kota Indonesia -> kembali ke daftar provinsi.
                if (currentCity && currentProvince) {

                    if (currentCityMarker) {
                        map.removeLayer(currentCityMarker);
                        currentCityMarker = null;
                    }

                    clearPlaceMarkers();
                    currentCity = null;

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
                    renderProvinceResults();
                    return;
                }

                // Jika sedang di level negara non-Indonesia -> kembali ke daftar negara.
                if (currentCountry && currentCountry.code !== 'id' && !currentProvince) {
                    currentCountry = null;
                    locationInfo.style.display = 'none';
                    searchInput.value = '';
                    renderCountryResults();
        prefetchRemoteProvinces();
                    return;
                }

                // Jika sedang di level provinsi -> kembali ke negara ASEAN.
                currentProvince = null;
                currentCity = null;

                clearPlaceMarkers();

                if (currentProvinceMarker) {
                    map.removeLayer(currentProvinceMarker);
                    currentProvinceMarker = null;
                }

                if (currentCountryMarker) {
                    map.removeLayer(currentCountryMarker);
                    currentCountryMarker = null;
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

                searchInput.value = '';
                currentCountry = null;
                renderCountryResults();
        prefetchRemoteProvinces();

                map.flyTo(
                    [1.0, 117.0],
                    4,
                    { animate: true, duration: 1.5 }
                );
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
           PREFETCH WILAYAH NEGARA LAIN
           Daftar provinsi/wilayah mulai diambil sejak halaman dibuka.
           Jadi saat user memilih negara, daftar biasanya sudah tersedia.
        ====================================================== */

        function prefetchRemoteProvinces() {
            aseanCountries
                .filter(country => country.code !== 'id')
                .forEach(country => {
                    loadRemoteProvinces(country);
                });
        }

        /* =====================================================
           INITIAL
        ====================================================== */

        renderCountryResults();
        prefetchRemoteProvinces();

    </script>

</body>

</html>