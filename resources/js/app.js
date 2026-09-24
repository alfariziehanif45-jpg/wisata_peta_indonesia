import './bootstrap';


/*
|--------------------------------------------------------------------------
| OPENING INDONESIA
|--------------------------------------------------------------------------
*/


const opening =
    document.getElementById('opening');


const openingVideo =
    document.getElementById('openingVideo');


const indonesiaVideos = [

    '/videos/indonesia-1.mp4',

    '/videos/indonesia-2.mp4',

    '/videos/indonesia-3.mp4',

    '/videos/indonesia-4.mp4',

    '/videos/indonesia-5.mp4'

];


let currentVideo = 0;


/*
|--------------------------------------------------------------------------
| PLAY VIDEO
|--------------------------------------------------------------------------
*/

function playNextVideo() {

    if (!openingVideo) {

        return;

    }


    openingVideo.src =
        indonesiaVideos[currentVideo];


    openingVideo.load();


    openingVideo.play()
        .catch(() => {

            console.log(
                'Browser menunggu izin untuk memutar video.'
            );

        });


    currentVideo++;


    if (
        currentVideo >=
        indonesiaVideos.length
    ) {

        currentVideo = 0;

    }

}


/*
|--------------------------------------------------------------------------
| VIDEO SELESAI
|--------------------------------------------------------------------------
*/

if (openingVideo) {

    openingVideo.addEventListener(
        'ended',
        playNextVideo
    );


    playNextVideo();

}


/*
|--------------------------------------------------------------------------
| TUTUP OPENING
|--------------------------------------------------------------------------
*/

function closeOpening() {

    if (!opening) {

        return;

    }


    opening.classList.add(
        'hide-opening'
    );


    setTimeout(() => {

        opening.remove();

    }, 1600);

}


/*
|--------------------------------------------------------------------------
| OPENING SELAMA 10 DETIK
|--------------------------------------------------------------------------
*/

if (opening) {

    setTimeout(() => {

        closeOpening();

    }, 10000);

}


/*
|--------------------------------------------------------------------------
| MAP
|--------------------------------------------------------------------------
*/

let map = null;


/*
|--------------------------------------------------------------------------
| SEARCH LOCATION ELEMENT
|--------------------------------------------------------------------------
*/

const searchButton =
    document.getElementById(
        'searchButton'
    );


const searchPanel =
    document.getElementById(
        'searchPanel'
    );


const closeSearch =
    document.getElementById(
        'closeSearch'
    );


const citySearch =
    document.getElementById(
        'citySearch'
    );


const cityList =
    document.getElementById(
        'cityList'
    );


const noResult =
    document.getElementById(
        'noResult'
    );


const locationInfo =
    document.getElementById(
        'locationInfo'
    );


const locationName =
    document.getElementById(
        'locationName'
    );


const closeLocation =
    document.getElementById(
        'closeLocation'
    );


const mapLoading =
    document.getElementById(
        'mapLoading'
    );


/*
|--------------------------------------------------------------------------
| INISIALISASI MAP
|--------------------------------------------------------------------------
*/

function initializeMap() {

    if (
        typeof L === 'undefined'
    ) {

        console.error(
            'Leaflet belum tersedia.'
        );

        return;

    }


    const mapElement =
        document.getElementById('map');


    if (!mapElement) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | CREATE MAP
    |--------------------------------------------------------------------------
    */

    map = L.map(
        'map',
        {
            zoomControl: true,

            attributionControl: true
        }
    );


    /*
    |--------------------------------------------------------------------------
    | OPENSTREETMAP
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | TAMBAHKAN TILE
    |--------------------------------------------------------------------------
    */

    osm.addTo(map);


    /*
    |--------------------------------------------------------------------------
    | POSISI AWAL INDONESIA
    |--------------------------------------------------------------------------
    */

    map.setView(

        [-2.5, 118],

        5

    );

}


/*
|--------------------------------------------------------------------------
| JALANKAN MAP
|--------------------------------------------------------------------------
*/

initializeMap();


/*
|--------------------------------------------------------------------------
| BUKA SEARCH
|--------------------------------------------------------------------------
*/

if (searchButton) {

    searchButton.addEventListener(
        'click',
        () => {

            searchPanel.classList.add(
                'active'
            );


            setTimeout(() => {

                if (citySearch) {

                    citySearch.focus();

                }

            }, 300);

        }
    );

}


/*
|--------------------------------------------------------------------------
| TUTUP SEARCH
|--------------------------------------------------------------------------
*/

if (closeSearch) {

    closeSearch.addEventListener(
        'click',
        () => {

            searchPanel.classList.remove(
                'active'
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| SEARCH KOTA
|--------------------------------------------------------------------------
*/

if (citySearch) {

    citySearch.addEventListener(
        'input',
        function () {

            const keyword =
                this.value
                    .toLowerCase()
                    .trim();


            const cities =
                document.querySelectorAll(
                    '.city-item'
                );


            let found = 0;


            cities.forEach(
                function (city) {

                    const name =
                        (
                            city.dataset.name ||
                            ''
                        )
                        .toLowerCase();


                    const province =
                        (
                            city.dataset.province ||
                            ''
                        )
                        .toLowerCase();


                    const type =
                        (
                            city.dataset.type ||
                            ''
                        )
                        .toLowerCase();


                    const match =

                        name.includes(
                            keyword
                        )

                        ||

                        province.includes(
                            keyword
                        )

                        ||

                        type.includes(
                            keyword
                        );


                    if (match) {

                        city.style.display =
                            'flex';

                        found++;

                    } else {

                        city.style.display =
                            'none';

                    }

                }
            );


            if (found === 0) {

                noResult.style.display =
                    'block';

            } else {

                noResult.style.display =
                    'none';

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| KLIK KOTA
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'click',
    function (event) {

        const cityItem =
            event.target.closest(
                '.city-item'
            );


        if (!cityItem) {

            return;

        }


        const cityName =
            cityItem.dataset.name;


        const provinceName =
            cityItem.dataset.province;


        const type =
            cityItem.dataset.type;


        searchOpenStreetMap(

            cityName,

            provinceName,

            type

        );

    }
);


/*
|--------------------------------------------------------------------------
| CARI LOKASI DI OPENSTREETMAP
|--------------------------------------------------------------------------
*/

async function searchOpenStreetMap(

    cityName,

    provinceName,

    type

) {

    if (!map) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | TAMPILKAN LOADING
    |--------------------------------------------------------------------------
    */

    if (mapLoading) {

        mapLoading.classList.add(
            'show'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | QUERY LEBIH SPESIFIK
    |--------------------------------------------------------------------------
    */

    let query;


    if (type === 'Kabupaten') {

        query =
            `Kabupaten ${cityName}, ${provinceName}, Indonesia`;

    } else {

        query =
            `Kota ${cityName}, ${provinceName}, Indonesia`;

    }


    /*
    |--------------------------------------------------------------------------
    | URL NOMINATIM
    |--------------------------------------------------------------------------
    */

    const url =
        'https://nominatim.openstreetmap.org/search?' +

        new URLSearchParams({

            q: query,

            format: 'jsonv2',

            limit: '1',

            countrycodes: 'id',

            addressdetails: '1'

        });


    try {

        /*
        |--------------------------------------------------------------------------
        | REQUEST
        |--------------------------------------------------------------------------
        */

        const response =
            await fetch(url);


        if (!response.ok) {

            throw new Error(
                'Gagal menghubungi OpenStreetMap.'
            );

        }


        const results =
            await response.json();


        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA HASIL
        |--------------------------------------------------------------------------
        */

        if (
            !results ||
            results.length === 0
        ) {

            alert(
                `Lokasi ${cityName} tidak ditemukan di OpenStreetMap.`
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | HASIL PERTAMA
        |--------------------------------------------------------------------------
        */

        const result =
            results[0];


        const latitude =
            parseFloat(
                result.lat
            );


        const longitude =
            parseFloat(
                result.lon
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDASI KOORDINAT
        |--------------------------------------------------------------------------
        */

        if (
            Number.isNaN(latitude) ||
            Number.isNaN(longitude)
        ) {

            throw new Error(
                'Koordinat tidak valid.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FLY TO LOKASI
        |--------------------------------------------------------------------------
        */

        map.flyTo(

            [
                latitude,
                longitude
            ],

            13,

            {

                animate: true,

                duration: 1.8,

                easeLinearity: 0.15

            }

        );


        /*
        |--------------------------------------------------------------------------
        | TUTUP SEARCH
        |--------------------------------------------------------------------------
        */

        searchPanel.classList.remove(
            'active'
        );


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN INFORMASI LOKASI
        |--------------------------------------------------------------------------
        */

        if (locationName) {

            locationName.textContent =
                result.display_name;

        }


        if (locationInfo) {

            locationInfo.classList.add(
                'show'
            );

        }

    }

    catch (error) {

        console.error(
            'OpenStreetMap Error:',
            error
        );


        alert(
            'Gagal mendapatkan lokasi dari OpenStreetMap. Silakan coba lagi.'
        );

    }

    finally {

        /*
        |--------------------------------------------------------------------------
        | HILANGKAN LOADING
        |--------------------------------------------------------------------------
        */

        if (mapLoading) {

            mapLoading.classList.remove(
                'show'
            );

        }

    }

}


/*
|--------------------------------------------------------------------------
| TUTUP INFO LOKASI
|--------------------------------------------------------------------------
*/

if (closeLocation) {

    closeLocation.addEventListener(
        'click',
        () => {

            locationInfo.classList.remove(
                'show'
            );

        }
    );

}