<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $province->name }}
    </title>


    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >


    <style>

        body {

            margin: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }


        .header {

            background: #198754;

            color: white;

            padding: 20px 30px;
        }


        .header a {

            color: white;

            text-decoration: none;
        }


        #map {

            height: 500px;
        }


        .content {

            padding: 30px;
        }


        .city-card {

            padding: 15px;

            margin-bottom: 15px;

            border:
                1px solid #ddd;

            border-radius: 10px;
        }


        .city-card a {

            text-decoration: none;

            color: #198754;
        }

    </style>

</head>


<body>


<div class="header">

    <a href="{{ route('map.index') }}">
        ← Kembali
    </a>


    <h1>
        {{ $province->name }}
    </h1>


    <p>
        {{ $province->description }}
    </p>

</div>


<div id="map"></div>


<div class="content">

    <h2>
        Kota / Kabupaten
    </h2>


    @forelse($province->cities as $city)

        <div class="city-card">

            <h3>

                <a
                    href="{{ route('map.city', $city->slug) }}"
                >

                    📍
                    {{ $city->name }}

                </a>

            </h3>


            <p>

                {{ $city->description }}

            </p>

        </div>

    @empty

        <p>
            Belum ada data kota/kabupaten.
        </p>

    @endforelse

</div>


<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
</script>


<script>

    /*
    =====================================================
    MAP PROVINSI
    =====================================================
    */

    const map =
        L.map('map')
        .setView(

            [
                {{ $province->latitude }},
                {{ $province->longitude }}
            ],

            8

        );


    /*
    =====================================================
    TILE
    =====================================================
    */

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {

            attribution:
                '&copy; OpenStreetMap contributors'

        }
    ).addTo(map);


    /*
    =====================================================
    MARKER KOTA
    =====================================================
    */

    @foreach($province->cities as $city)

        @if(
            $city->latitude !== null &&
            $city->longitude !== null
        )

            L.marker([

                {{ $city->latitude }},

                {{ $city->longitude }}

            ])

            .addTo(map)

            .bindPopup(`

                <strong>
                    {{ $city->name }}
                </strong>

                <br>

                <small>
                    {{ $city->type }}
                </small>

                <br><br>

                <a
                    href="{{ route('map.city', $city->slug) }}"
                    style="
                        color:#198754;
                        font-weight:bold;
                        text-decoration:none;
                    "
                >
                    Lihat kota →
                </a>

            `);

        @endif

    @endforeach

</script>


</body>

</html>