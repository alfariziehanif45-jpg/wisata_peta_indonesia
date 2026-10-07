<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncIndonesiaCoordinates extends Command
{
    protected $signature = 'map:sync-indonesia-coordinates';

    protected $description = 'Sinkronkan koordinat 38 provinsi dan 514 kabupaten/kota Indonesia.';

    public function handle(): int
    {
        $indonesia = Country::where('code', 'ID')->first();

        if (!$indonesia) {
            $this->error('Negara Indonesia belum tersedia di tabel countries.');
            return self::FAILURE;
        }

        $provinceCodes = $this->provinceCodes();
        $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/v2';
        $http = Http::timeout(20)
            ->connectTimeout(8)
            ->withHeaders([
                'User-Agent' => 'JelajahWisataIndonesia/1.0 Laravel Tourism Map',
                'Accept' => 'application/json',
            ]);

        $provinceUpdated = 0;
        $cityUpdated = 0;

        $provinces = Province::where('country_id', $indonesia->id)
            ->orderBy('name')
            ->get();

        foreach ($provinces as $province) {
            $code = $this->provinceCode((string) $province->name, $provinceCodes);

            if (!$code) {
                $this->warn("Lewati provinsi tanpa kode: {$province->name}");
                continue;
            }

            $provinceResponse = $http->get($baseUrl . '/provinces/' . $code . '.json');

            if ($provinceResponse->successful()) {
                $row = $provinceResponse->json()['data'] ?? null;

                if (is_array($row) && isset($row['lat'], $row['lng'])) {
                    $province->latitude = (float) $row['lat'];
                    $province->longitude = (float) $row['lng'];
                    $province->save();
                    $provinceUpdated++;
                }
            } else {
                $this->warn("Gagal mengambil koordinat provinsi {$province->name} (HTTP {$provinceResponse->status()})");
            }

            $regencyResponse = $http->get($baseUrl . '/regencies/' . $code . '.json');

            if (!$regencyResponse->successful()) {
                $this->warn("Gagal mengambil kota/kabupaten {$province->name} (HTTP {$regencyResponse->status()})");
                continue;
            }

            $rows = $regencyResponse->json()['data'] ?? [];
            if (!is_array($rows)) {
                continue;
            }

            $cities = City::where('province_id', $province->id)->get();

            foreach ($rows as $row) {
                if (!is_array($row) || !isset($row['name'], $row['lat'], $row['lng'])) {
                    continue;
                }

                $target = $this->normaliseCity($row['name']);
                $city = $cities->first(function (City $item) use ($target) {
                    return $this->normaliseCity($item->name) === $target;
                });

                if (!$city) {
                    continue;
                }

                $city->latitude = (float) $row['lat'];
                $city->longitude = (float) $row['lng'];

                $rawName = Str::lower(trim((string) $row['name']));
                if (str_starts_with($rawName, 'kota ')) {
                    $city->type = 'Kota';
                } elseif (str_starts_with($rawName, 'kabupaten ')) {
                    $city->type = 'Kabupaten';
                }

                $city->save();
                $cityUpdated++;
            }

            $this->line("✓ {$province->name}: koordinat provinsi + kota/kabupaten diperbarui");
        }

        $this->newLine();
        $this->info("Selesai. Provinsi diperbarui: {$provinceUpdated}. Kota/kabupaten diperbarui: {$cityUpdated}.");

        return self::SUCCESS;
    }

    private function provinceCodes(): array
    {
        return [
            'aceh' => '11',
            'sumaterautara' => '12',
            'sumaterabarat' => '13',
            'riau' => '14',
            'jambi' => '15',
            'sumateraselatan' => '16',
            'bengkulu' => '17',
            'lampung' => '18',
            'kepulauanbangkabelitung' => '19',
            'kepulauanriau' => '21',
            'dkijakarta' => '31',
            'jakarta' => '31',
            'jawabarat' => '32',
            'jawatengah' => '33',
            'daerahistimewayogyakarta' => '34',
            'diyogyakarta' => '34',
            'yogyakarta' => '34',
            'jawatimur' => '35',
            'banten' => '36',
            'bali' => '51',
            'nusatenggarabarat' => '52',
            'nusatenggaratimur' => '53',
            'kalimantanbarat' => '61',
            'kalimantantengah' => '62',
            'kalimantanselatan' => '63',
            'kalimantantimur' => '64',
            'kalimantanutara' => '65',
            'sulawesiutara' => '71',
            'sulawesitengah' => '72',
            'sulawesiselatan' => '73',
            'sulawesitenggara' => '74',
            'gorontalo' => '75',
            'sulawesibarat' => '76',
            'maluku' => '81',
            'malukuutara' => '82',
            'papua' => '91',
            'papuabarat' => '92',
            'papuaselatan' => '93',
            'papuatengah' => '94',
            'papuapegunungan' => '95',
            'papuabaratdaya' => '96',
        ];
    }

    private function provinceCode(string $name, array $codes): ?string
    {
        $key = Str::ascii(Str::lower(trim($name)));
        $key = str_replace(['provinsi ', 'daerah khusus ibukota ', 'daerah khusus '], '', $key);
        $key = preg_replace('/[^a-z0-9]+/', '', $key) ?? '';
        return $codes[$key] ?? null;
    }

    private function normaliseCity(string $value): string
    {
        $value = Str::ascii(Str::lower(trim($value)));
        $value = preg_replace('/^(kabupaten|kota|kota administrasi)\s+/i', '', $value) ?? $value;
        return preg_replace('/[^a-z0-9]+/', '', $value) ?? '';
    }
}
