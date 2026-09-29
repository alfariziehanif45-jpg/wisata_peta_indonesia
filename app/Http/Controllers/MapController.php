<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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

    public function countries()
    {
        return response()->json(
            Country::orderBy('name')->get([
                'id', 'name', 'code', 'latitude', 'longitude'
            ])
        );
    }

    public function provinces($countryId)
    {
        $provinces = Province::where('country_id', $countryId)
            ->orderBy('name')
            ->get([
                'id', 'country_id', 'name', 'type', 'slug',
                'latitude', 'longitude'
            ]);

        return response()->json($provinces);
    }

    public function cities($provinceId)
    {
        $cities = City::where('province_id', $provinceId)
            ->orderBy('name')
            ->get([
                'id', 'province_id', 'name', 'type', 'slug',
                'latitude', 'longitude'
            ]);

        return response()->json($cities);
    }


    public function regions(string $countryCode)
    {
        $countryCode = strtoupper(trim($countryCode));

        if (!preg_match('/^[A-Z]{2}$/', $countryCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode negara tidak valid.',
            ], 422);
        }

        $countryNames = [
            'BN' => 'Brunei Darussalam',
            'KH' => 'Cambodia',
            'LA' => 'Laos',
            'MY' => 'Malaysia',
            'MM' => 'Myanmar',
            'PH' => 'Philippines',
            'SG' => 'Singapore',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'VN' => 'Vietnam',
        ];

        $countryName = $countryNames[$countryCode] ?? null;
        if (!$countryName) {
            return response()->json([
                'success' => false,
                'message' => 'Negara belum tersedia untuk data wilayah otomatis.',
            ], 404);
        }

        $cacheKey = 'map.regions.countriesnow.' . strtolower($countryCode);

        try {
            $regions = Cache::remember($cacheKey, now()->addDays(30), function () use ($countryName, $countryCode) {
                $response = Http::timeout(20)
                    ->connectTimeout(8)
                    ->withHeaders([
                        'User-Agent' => 'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                        'Accept' => 'application/json',
                    ])
                    ->get('https://countriesnow.space/api/v0.1/countries/states/q', [
                        'country' => $countryName,
                    ]);

                if (!$response->successful()) {
                    throw new \RuntimeException('CountriesNow HTTP ' . $response->status());
                }

                $data = $response->json();
                if (($data['error'] ?? true) === true) {
                    throw new \RuntimeException($data['msg'] ?? 'Data wilayah tidak tersedia.');
                }

                $states = $data['data']['states'] ?? [];
                $result = [];

                foreach ($states as $state) {
                    $name = trim((string) ($state['name'] ?? ''));
                    if ($name === '') {
                        continue;
                    }

                    $result[] = [
                        'id' => $state['state_code'] ?? $name,
                        'osm_id' => null,
                        'state_code' => $state['state_code'] ?? null,
                        'name' => $name,
                        'type' => 'Wilayah',
                        'admin_level' => 4,
                        'latitude' => null,
                        'longitude' => null,
                        'osm_type' => null,
                        'country_code' => strtolower($countryCode),
                    ];
                }

                usort($result, fn ($a, $b) => strcasecmp($a['name'], $b['name']));
                return $result;
            });

            if (empty($regions)) {
                throw new \RuntimeException('CountriesNow mengembalikan daftar wilayah kosong.');
            }

            return response()->json([
                'success' => true,
                'country_code' => strtolower($countryCode),
                'count' => count($regions),
                'regions' => $regions,
                'source' => 'CountriesNow',
                'cached' => true,
            ]);
        } catch (\Throwable $e) {
            // Fallback ke OSM jika CountriesNow sedang tidak tersedia.
            try {
                $regions = $this->regionsFromOverpass($countryCode);

                return response()->json([
                    'success' => true,
                    'country_code' => strtolower($countryCode),
                    'count' => count($regions),
                    'regions' => $regions,
                    'source' => 'OpenStreetMap',
                    'cached' => false,
                ]);
            } catch (\Throwable $fallbackError) {
                return response()->json([
                    'success' => false,
                    'message' => 'Daftar wilayah belum dapat dimuat. Silakan coba lagi.',
                    'error' => config('app.debug')
                        ? $e->getMessage() . ' | Fallback: ' . $fallbackError->getMessage()
                        : null,
                ], 503);
            }
        }
    }

    public function remoteCities(Request $request, string $countryCode)
    {
        $countryCode = strtoupper(trim($countryCode));
        $regionName = trim((string) $request->query('region_name', ''));

        if (!preg_match('/^[A-Z]{2}$/', $countryCode) || $regionName === '') {
            return response()->json([
                'success' => false,
                'message' => 'Negara atau wilayah tidak valid.',
            ], 422);
        }

        $countryNames = [
            'BN' => 'Brunei Darussalam',
            'KH' => 'Cambodia',
            'LA' => 'Laos',
            'MY' => 'Malaysia',
            'MM' => 'Myanmar',
            'PH' => 'Philippines',
            'SG' => 'Singapore',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'VN' => 'Vietnam',
        ];

        $countryName = $countryNames[$countryCode] ?? null;
        if (!$countryName) {
            return response()->json([
                'success' => false,
                'message' => 'Negara belum tersedia.',
            ], 404);
        }

        if ($countryCode === 'SG') {
            $cities = [[
                'id' => 'SG-SIN',
                'name' => 'Singapore',
                'type' => 'Kota',
                'latitude' => 1.3521,
                'longitude' => 103.8198,
                'display_name' => 'Singapore • Singapore',
                'country_code' => 'sg',
            ]];

            return response()->json([
                'success' => true,
                'country_code' => 'sg',
                'region_name' => $regionName,
                'count' => 1,
                'cities' => $cities,
                'source' => 'Local',
                'cached' => true,
            ]);
        }

        $cacheKey = 'map.cities.countriesnow.' . strtolower($countryCode) . '.' . sha1(strtolower($regionName));

        try {
            $cities = Cache::remember($cacheKey, now()->addDays(30), function () use ($countryName, $countryCode, $regionName) {
                $response = Http::timeout(20)
                    ->connectTimeout(8)
                    ->withHeaders([
                        'User-Agent' => 'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                        'Accept' => 'application/json',
                    ])
                    ->get('https://countriesnow.space/api/v0.1/countries/state/cities/q', [
                        'country' => $countryName,
                        'state' => $regionName,
                    ]);

                if (!$response->successful()) {
                    throw new \RuntimeException('CountriesNow HTTP ' . $response->status());
                }

                $data = $response->json();
                if (($data['error'] ?? true) === true) {
                    throw new \RuntimeException($data['msg'] ?? 'Data kota tidak tersedia.');
                }

                $rawCities = $data['data'] ?? [];
                $result = [];
                $used = [];

                foreach ($rawCities as $city) {
                    $name = is_array($city)
                        ? trim((string) ($city['name'] ?? ''))
                        : trim((string) $city);

                    if ($name === '') {
                        continue;
                    }

                    $key = strtolower($name);
                    if (isset($used[$key])) {
                        continue;
                    }
                    $used[$key] = true;

                    $result[] = [
                        'id' => is_array($city) ? ($city['id'] ?? null) : null,
                        'name' => $name,
                        'type' => 'Kota/Kabupaten',
                        'latitude' => is_array($city) && isset($city['latitude']) ? (float) $city['latitude'] : null,
                        'longitude' => is_array($city) && isset($city['longitude']) ? (float) $city['longitude'] : null,
                        'display_name' => $name . ' • ' . $regionName,
                        'country_code' => strtolower($countryCode),
                    ];
                }

                usort($result, fn ($a, $b) => strcasecmp($a['name'], $b['name']));
                return $result;
            });

            return response()->json([
                'success' => true,
                'country_code' => strtolower($countryCode),
                'region_name' => $regionName,
                'count' => count($cities),
                'cities' => $cities,
                'source' => 'CountriesNow',
                'cached' => true,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Daftar kota belum dapat dimuat. Coba pilih wilayah lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 503);
        }
    }

    private function regionsFromOverpass(string $countryCode): array
    {
        $query = <<<OVERPASS
[out:json][timeout:45];
area["ISO3166-1"="$countryCode"]["boundary"="administrative"]->.country;
relation(area.country)["boundary"="administrative"]["admin_level"~"4|5"];
out center tags;
OVERPASS;

        $servers = [
            'https://lz4.overpass-api.de/api/interpreter',
            'https://z.overpass-api.de/api/interpreter',
            'https://overpass-api.de/api/interpreter',
        ];

        $lastError = null;

        foreach ($servers as $server) {
            try {
                $response = Http::timeout(60)
                    ->connectTimeout(15)
                    ->withHeaders([
                        'User-Agent' => 'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                        'Accept' => 'application/json',
                    ])
                    ->asForm()
                    ->post($server, ['data' => $query]);

                if (!$response->successful()) {
                    $lastError = 'HTTP ' . $response->status();
                    continue;
                }

                $data = $response->json();
                $elements = $data['elements'] ?? [];
                $levels = [];

                foreach ($elements as $element) {
                    $level = (int) ($element['tags']['admin_level'] ?? 0);
                    if ($level > 0) {
                        $levels[] = $level;
                    }
                }

                $preferredLevel = !empty($levels) ? min($levels) : 4;
                $regions = [];
                $used = [];

                foreach ($elements as $element) {
                    $tags = $element['tags'] ?? [];
                    $level = (int) ($tags['admin_level'] ?? 0);
                    $name = trim((string) ($tags['name'] ?? ''));
                    if ($level !== $preferredLevel || $name === '') {
                        continue;
                    }

                    $lat = $element['center']['lat'] ?? null;
                    $lon = $element['center']['lon'] ?? null;
                    if ($lat === null || $lon === null) {
                        continue;
                    }

                    $key = strtolower($name);
                    if (isset($used[$key])) {
                        continue;
                    }
                    $used[$key] = true;

                    $regions[] = [
                        'id' => $element['id'] ?? null,
                        'osm_id' => $element['id'] ?? null,
                        'state_code' => null,
                        'name' => $name,
                        'type' => 'Wilayah',
                        'admin_level' => $level,
                        'latitude' => (float) $lat,
                        'longitude' => (float) $lon,
                        'osm_type' => $element['type'] ?? 'relation',
                        'country_code' => strtolower($countryCode),
                    ];
                }

                usort($regions, fn ($a, $b) => strcasecmp($a['name'], $b['name']));
                return $regions;
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        throw new \RuntimeException($lastError ?: 'Data wilayah OpenStreetMap tidak tersedia.');
    }

    public function locationSearch(Request $request)
    {
        $name = trim((string) $request->query('name', ''));
        $type = trim((string) $request->query('type', 'city'));
        $province = trim((string) $request->query('province', ''));
        $countryCode = strtolower(trim((string) $request->query('country_code', '')));
        $countryName = trim((string) $request->query('country_name', ''));

        if ($name === '' || strlen($name) < 2) {
            return response()->json([
                'success' => true,
                'results' => [],
            ]);
        }

        if (!in_array($type, ['province', 'city'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe lokasi tidak valid.',
            ], 422);
        }

        $params = [
            'q' => $name . ($province !== '' ? ', ' . $province : '') . ($countryName !== '' ? ', ' . $countryName : ''),
            'format' => 'jsonv2',
            'limit' => 10,
            'addressdetails' => 1,
            'layer' => 'address',
            'featureType' => $type === 'province' ? 'state' : 'city',
        ];

        if ($countryCode !== '') {
            $params['countrycodes'] = $countryCode;
        }

        try {
            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->withHeaders([
                    'User-Agent' => 'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                    'Accept' => 'application/json',
                ])
                ->get('https://nominatim.openstreetmap.org/search', $params);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'OpenStreetMap gagal melakukan pencarian.',
                ], 502);
            }

            $results = [];
            $used = [];

            foreach (($response->json() ?? []) as $item) {
                if (!isset($item['lat'], $item['lon'])) {
                    continue;
                }

                $address = $item['address'] ?? [];
                $resultName = $type === 'city'
                    ? ($address['city'] ?? $address['town'] ?? $address['municipality'] ?? $address['village'] ?? null)
                    : ($address['state'] ?? null);

                if (!$resultName) {
                    $resultName = $item['name'] ?? null;
                }

                if (!$resultName) {
                    continue;
                }

                $key = strtolower($resultName . '|' . $item['lat'] . '|' . $item['lon']);
                if (isset($used[$key])) {
                    continue;
                }
                $used[$key] = true;

                $results[] = [
                    'name' => $resultName,
                    'type' => $type === 'city' ? (isset($address['city']) ? 'Kota' : 'Kota/Kabupaten') : 'Region',
                    'latitude' => (float) $item['lat'],
                    'longitude' => (float) $item['lon'],
                    'display_name' => $item['display_name'] ?? $resultName,
                    'country_code' => strtolower((string) ($address['country_code'] ?? $countryCode)),
                ];
            }

            return response()->json([
                'success' => true,
                'results' => $results,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari lokasi.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function geocode(Request $request)
    {
        $name = trim((string) $request->query('name', ''));
        $type = trim((string) $request->query('type', ''));
        $province = trim((string) $request->query('province', ''));
        $cityType = trim((string) $request->query('city_type', ''));
        $countryCode = strtolower(trim((string) $request->query('country_code', '')));
        $countryName = trim((string) $request->query('country_name', ''));

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

            if ($countryName !== '') {
                $searchName .= ', ' . $countryName;
            }

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'layer' => 'address',
                'featureType' => 'city',
                'addressdetails' => 1,
            ];

            if ($countryCode !== '') {
                $params['countrycodes'] = $countryCode;
            }
        } elseif ($type === 'province') {
            $searchName = $name;

            if ($countryName !== '') {
                $searchName .= ', ' . $countryName;
            }

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'layer' => 'address',
                'featureType' => 'state',
                'addressdetails' => 1,
            ];

            if ($countryCode !== '') {
                $params['countrycodes'] = $countryCode;
            }
        } elseif ($type === 'country') {
            $searchName = $name;

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'layer' => 'address',
                'featureType' => 'country',
                'addressdetails' => 1,
            ];

            if ($countryCode !== '') {
                $params['countrycodes'] = $countryCode;
            }
        } else {
            $searchName = $name;

            if ($countryName !== '') {
                $searchName .= ', ' . $countryName;
            }

            $params = [
                'q' => $searchName,
                'format' => 'jsonv2',
                'limit' => 10,
                'layer' => 'address',
                'addressdetails' => 1,
            ];

            if ($countryCode !== '') {
                $params['countrycodes'] = $countryCode;
            }
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
                        $type === 'country'
                            ? 'Negara tidak ditemukan di OpenStreetMap.'
                            : 'Wilayah tidak ditemukan di OpenStreetMap.',
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
                } elseif ($type === 'country') {
                    if ($addressType === 'country') {
                        $score += 200;
                    }

                    if ($candidateType === 'administrative') {
                        $score += 50;
                    }

                    if ($candidateClass === 'boundary') {
                        $score += 50;
                    }

                    if (isset($address['country'])) {
                        similar_text(
                            strtolower($name),
                            strtolower((string) $address['country']),
                            $percent
                        );

                        $score += (int) round($percent);
                    }

                    if (
                        $countryCode !== '' &&
                        strtolower((string) ($address['country_code'] ?? '')) === $countryCode
                    ) {
                        $score += 100;
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
