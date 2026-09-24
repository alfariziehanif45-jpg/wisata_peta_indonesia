<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCityCoordinates extends Command
{
    protected $signature = 'cities:update-coordinates';

    protected $description = 'Memperbarui koordinat kota berdasarkan OpenStreetMap';

    public function handle()
    {
        $cities = City::with('province')
            ->orderBy('id')
            ->get();

        if ($cities->isEmpty()) {
            $this->error('Data kota tidak ditemukan.');

            return Command::FAILURE;
        }

        $this->info('Memulai update koordinat...');
        $this->newLine();

        foreach ($cities as $city) {

            $cityName = trim($city->name);

            $provinceName = $city->province
                ? trim($city->province->name)
                : '';

            $type = $city->type === 'Kabupaten'
                ? 'Kabupaten '
                : 'Kota ';

            $query = $type
                . $cityName
                . ', '
                . $provinceName
                . ', Indonesia';

            $this->line("Mencari: {$query}");

            try {

                $response = Http::timeout(20)
                    ->withHeaders([
                        'User-Agent' => 'WisataIndonesiaLaravel/1.0',
                        'Accept-Language' => 'id',
                    ])
                    ->get(
                        'https://nominatim.openstreetmap.org/search',
                        [
                            'q' => $query,
                            'format' => 'jsonv2',
                            'limit' => 1,
                            'countrycodes' => 'id',
                        ]
                    );

                if (!$response->successful()) {
                    $this->error("Gagal: {$cityName}");
                    sleep(2);
                    continue;
                }

                $results = $response->json();

                if (empty($results)) {
                    $this->warn("Tidak ditemukan: {$cityName}");
                    sleep(1);
                    continue;
                }

                $result = $results[0];

                $latitude = $result['lat'] ?? null;
                $longitude = $result['lon'] ?? null;

                if (!$latitude || !$longitude) {
                    $this->error("Koordinat kosong: {$cityName}");
                    sleep(1);
                    continue;
                }

                $city->update([
                    'latitude' => (float) $latitude,
                    'longitude' => (float) $longitude,
                ]);

                $this->info(
                    "✓ {$cityName} => {$latitude}, {$longitude}"
                );

            } catch (\Throwable $e) {

                $this->error(
                    "Error {$cityName}: {$e->getMessage()}"
                );
            }

            sleep(1);
        }

        $this->newLine();

        $this->info('Update koordinat selesai.');

        return Command::SUCCESS;
    }
}