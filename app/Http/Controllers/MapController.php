<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function index()
    {
        $provinces = Province::orderBy('name')->get();

        $cities = City::with('province')
            ->orderBy('name')
            ->get();

        return view(
            'map.index',
            compact('provinces', 'cities')
        );
    }

    public function province($slug)
    {
        $province = Province::where('slug', $slug)
            ->firstOrFail();

        $provinces = Province::orderBy('name')->get();

        $cities = City::with('province')
            ->where('province_id', $province->id)
            ->orderBy('name')
            ->get();

        return view(
            'map.index',
            compact('provinces', 'province', 'cities')
        );
    }

    public function city($slug)
    {
        $city = City::with('province')
            ->where('slug', $slug)
            ->firstOrFail();

        $provinces = Province::orderBy('name')->get();

        $cities = City::with('province')
            ->orderBy('name')
            ->get();

        return view(
            'map.index',
            compact('provinces', 'cities', 'city')
        );
    }

    public function geocode(Request $request)
    {
        $name = trim((string) $request->query('name', ''));
        $type = trim((string) $request->query('type', ''));
        $province = trim((string) $request->query('province', ''));
        $cityType = trim((string) $request->query('city_type', ''));

        if ($name === '') {
            return response()->json([
                'success' => false,
                'message' => 'Nama lokasi tidak boleh kosong.',
            ], 422);
        }

        /*
         * PENTING:
         * Jangan melakukan pencarian kota sebagai POI umum.
         * Query seperti "Palembang, Indonesia" tanpa filter address
         * bisa mengembalikan sekolah/toko/POI bernama Palembang.
         * Untuk kota kita pakai layer=address + featureType=city,
         * sehingga titik yang dipilih berasal dari data wilayah kota.
         */
        if ($type === 'city') {
            $searchName = $name;

            if ($province !== '') {
                $searchName .= ', ' . $province;
            }

            $searchName .= ', Indonesia';

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'countrycodes' => 'id',
                'layer' => 'address',
                'featureType' => 'city',
                'addressdetails' => 1,
            ];
        } elseif ($type === 'province') {
            $searchName = $name . ', Indonesia';

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'countrycodes' => 'id',
                'layer' => 'address',
                'featureType' => 'state',
                'addressdetails' => 1,
            ];
        } else {
            $searchName = $name . ', Indonesia';

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'countrycodes' => 'id',
                'layer' => 'address',
                'addressdetails' => 1,
            ];
        }

        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders([
                    'User-Agent' =>
                        'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                    'Accept' => 'application/json',
                ])
                ->get(
                    'https://nominatim.openstreetmap.org/search',
                    $params
                );

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'OpenStreetMap gagal melakukan pencarian lokasi.',
                    'status' => $response->status(),
                ], 502);
            }

            $data = $response->json();

            if (!is_array($data) || count($data) === 0) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Wilayah kota tidak ditemukan di OpenStreetMap.',
                ], 404);
            }

            /*
             * Pilih hasil yang benar-benar merupakan wilayah kota,
             * bukan POI yang kebetulan mempunyai nama sama.
             */
            $location = null;
            $bestScore = -1;

            foreach ($data as $candidate) {
                if (!isset($candidate['lat'], $candidate['lon'])) {
                    continue;
                }

                $score = 0;
                $candidateType = strtolower(
                    (string) ($candidate['type'] ?? '')
                );
                $candidateClass = strtolower(
                    (string) ($candidate['class'] ?? '')
                );
                $addressType = strtolower(
                    (string) ($candidate['addresstype'] ?? '')
                );
                $address = $candidate['address'] ?? [];

                if ($type === 'city') {
                    if ($addressType === 'city') {
                        $score += 100;
                    }

                    if ($candidateType === 'administrative') {
                        $score += 80;
                    }

                    if ($candidateClass === 'boundary') {
                        $score += 50;
                    }

                    if (isset($address['city'])) {
                        similar_text(
                            strtolower($name),
                            strtolower((string) $address['city']),
                            $percent
                        );

                        $score += (int) round($percent);
                    }

                    if (
                        $cityType === 'Kota' &&
                        isset($address['city'])
                    ) {
                        $score += 20;
                    }

                    // Tolak hasil yang jelas-jelas berupa POI/bangunan.
                    if (in_array($candidateClass, [
                        'amenity',
                        'shop',
                        'building',
                        'tourism',
                        'leisure',
                        'place_of_worship',
                    ], true)) {
                        $score -= 200;
                    }
                } elseif ($type === 'province') {
                    if ($addressType === 'state') {
                        $score += 100;
                    }

                    if ($candidateType === 'administrative') {
                        $score += 80;
                    }

                    if ($candidateClass === 'boundary') {
                        $score += 50;
                    }

                    if (isset($address['state'])) {
                        similar_text(
                            strtolower($name),
                            strtolower((string) $address['state']),
                            $percent
                        );

                        $score += (int) round($percent);
                    }
                } else {
                    $score = 1;
                }

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $location = $candidate;
                }
            }

            if ($location === null) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Koordinat wilayah tidak tersedia.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'latitude' => (float) $location['lat'],
                'longitude' => (float) $location['lon'],
                'display_name' =>
                    $location['display_name'] ?? $searchName,
                'type' => $location['type'] ?? null,
                'class' => $location['class'] ?? null,
                'addresstype' => $location['addresstype'] ?? null,
                'osm_type' => $location['osm_type'] ?? null,
                'osm_id' => $location['osm_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Terjadi kesalahan saat menghubungi OpenStreetMap.',
                'error' => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }

    public function places(Request $request)
    {
        $category = strtolower(
            trim((string) $request->query('category', ''))
        );

        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');

        if ($latitude === null || $longitude === null) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat kota tidak tersedia.',
            ], 422);
        }

        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat tidak valid.',
            ], 422);
        }

        $latitude = (float) $latitude;
        $longitude = (float) $longitude;

        if (
            $latitude < -11 ||
            $latitude > 6 ||
            $longitude < 94 ||
            $longitude > 142
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Koordinat berada di luar wilayah Indonesia.',
            ], 422);
        }

        if (!in_array($category, ['wisata', 'kuliner'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tempat tidak valid.',
            ], 422);
        }

        if ($category === 'wisata') {
            $query = <<<OVERPASS
[out:json][timeout:60];
(
    nwr(around:10000,$latitude,$longitude)["tourism"="attraction"];
    nwr(around:10000,$latitude,$longitude)["tourism"="museum"];
    nwr(around:10000,$latitude,$longitude)["tourism"="gallery"];
    nwr(around:10000,$latitude,$longitude)["tourism"="theme_park"];
    nwr(around:10000,$latitude,$longitude)["tourism"="zoo"];
    nwr(around:10000,$latitude,$longitude)["tourism"="viewpoint"];
    nwr(around:10000,$latitude,$longitude)["historic"="monument"];
    nwr(around:10000,$latitude,$longitude)["historic"="castle"];
    nwr(around:10000,$latitude,$longitude)["leisure"="park"];
    nwr(around:10000,$latitude,$longitude)["natural"="beach"];
);
out center tags;
OVERPASS;
        } else {
            $query = <<<OVERPASS
[out:json][timeout:60];
(
    nwr(around:10000,$latitude,$longitude)["amenity"="restaurant"];
    nwr(around:10000,$latitude,$longitude)["amenity"="cafe"];
    nwr(around:10000,$latitude,$longitude)["amenity"="fast_food"];
    nwr(around:10000,$latitude,$longitude)["amenity"="food_court"];
    nwr(around:10000,$latitude,$longitude)["amenity"="ice_cream"];
);
out center tags;
OVERPASS;
        }

        $servers = [
            'https://lz4.overpass-api.de/api/interpreter',
            'https://z.overpass-api.de/api/interpreter',
            'https://overpass-api.de/api/interpreter',
        ];

        $lastError = null;

        foreach ($servers as $server) {
            try {
                $response = Http::timeout(90)
                    ->connectTimeout(20)
                    ->withHeaders([
                        'User-Agent' =>
                            'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                        'Accept' => 'application/json',
                    ])
                    ->asForm()
                    ->post(
                        $server,
                        ['data' => $query]
                    );

                if ($response->successful()) {
                    $data = $response->json();

                    if (
                        !is_array($data) ||
                        !isset($data['elements'])
                    ) {
                        $lastError =
                            'Format data Overpass tidak valid.';
                        continue;
                    }

                    $places = [];
                    $used = [];

                    foreach ($data['elements'] as $element) {
                        $osmType = $element['type'] ?? '';
                        $osmId = $element['id'] ?? null;

                        if ($osmType === '' || $osmId === null) {
                            continue;
                        }

                        $uniqueKey = $osmType . '-' . $osmId;

                        if (isset($used[$uniqueKey])) {
                            continue;
                        }

                        $used[$uniqueKey] = true;

                        $tags = $element['tags'] ?? [];

                        $name =
                            $tags['name'] ??
                            $tags['name:id'] ??
                            null;

                        if (!$name || trim($name) === '') {
                            continue;
                        }

                        if (
                            isset($element['lat']) &&
                            isset($element['lon'])
                        ) {
                            $placeLatitude = (float) $element['lat'];
                            $placeLongitude = (float) $element['lon'];
                        } elseif (
                            isset($element['center']['lat']) &&
                            isset($element['center']['lon'])
                        ) {
                            $placeLatitude =
                                (float) $element['center']['lat'];
                            $placeLongitude =
                                (float) $element['center']['lon'];
                        } else {
                            continue;
                        }

                        $distance = $this->calculateDistance(
                            $latitude,
                            $longitude,
                            $placeLatitude,
                            $placeLongitude
                        );

                        if ($category === 'wisata') {
                            if (isset($tags['tourism'])) {
                                $placeType = $tags['tourism'];
                            } elseif (isset($tags['historic'])) {
                                $placeType = $tags['historic'];
                            } elseif (isset($tags['leisure'])) {
                                $placeType = $tags['leisure'];
                            } elseif (isset($tags['natural'])) {
                                $placeType = $tags['natural'];
                            } else {
                                $placeType = 'wisata';
                            }
                        } else {
                            $placeType =
                                $tags['amenity'] ?? 'kuliner';
                        }

                        $places[] = [
                            'id' => $osmId,
                            'osm_type' => $osmType,
                            'name' => $name,
                            'latitude' => $placeLatitude,
                            'longitude' => $placeLongitude,
                            'distance' => round($distance, 2),
                            'type' => $placeType,
                            'category' => $category,
                            'address' =>
                                $tags['addr:full'] ??
                                $tags['addr:street'] ??
                                null,
                            'phone' =>
                                $tags['phone'] ??
                                $tags['contact:phone'] ??
                                null,
                            'website' =>
                                $tags['website'] ??
                                $tags['contact:website'] ??
                                null,
                        ];
                    }

                    usort(
                        $places,
                        fn ($a, $b) =>
                            $a['distance'] <=> $b['distance']
                    );

                    $places = array_slice($places, 0, 100);

                    return response()->json([
                        'success' => true,
                        'category' => $category,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'count' => count($places),
                        'places' => $places,
                    ]);
                }

                $lastError =
                    'HTTP ' .
                    $response->status() .
                    ': ' .
                    $response->body();
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        return response()->json([
            'success' => false,
            'message' =>
                'Server data OpenStreetMap sedang sibuk. Silakan coba lagi beberapa saat lagi.',
            'error' => config('app.debug') ? $lastError : null,
        ], 503);
    }

    private function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
                cos(deg2rad($lat2)) *
                sin($dLon / 2) *
                sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
