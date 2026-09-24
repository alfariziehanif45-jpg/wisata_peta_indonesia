<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $provinces = [

            [
                'name' => 'Aceh',
                'latitude' => 4.6951,
                'longitude' => 96.7494,
                'description' => 'Provinsi Aceh di ujung barat Indonesia.'
            ],

            [
                'name' => 'Sumatera Utara',
                'latitude' => 2.1154,
                'longitude' => 99.5451,
                'description' => 'Provinsi Sumatera Utara.'
            ],

            [
                'name' => 'Sumatera Barat',
                'latitude' => -0.7399,
                'longitude' => 100.8000,
                'description' => 'Provinsi Sumatera Barat.'
            ],

            [
                'name' => 'Riau',
                'latitude' => 0.2933,
                'longitude' => 101.7068,
                'description' => 'Provinsi Riau.'
            ],

            [
                'name' => 'Jambi',
                'latitude' => -1.4852,
                'longitude' => 102.4381,
                'description' => 'Provinsi Jambi.'
            ],

            [
                'name' => 'Sumatera Selatan',
                'latitude' => -3.3194,
                'longitude' => 104.9146,
                'description' => 'Provinsi Sumatera Selatan.'
            ],

            [
                'name' => 'Bengkulu',
                'latitude' => -3.5778,
                'longitude' => 102.3464,
                'description' => 'Provinsi Bengkulu.'
            ],

            [
                'name' => 'Lampung',
                'latitude' => -4.5586,
                'longitude' => 105.4068,
                'description' => 'Provinsi Lampung.'
            ],

            [
                'name' => 'Kepulauan Bangka Belitung',
                'latitude' => -2.7411,
                'longitude' => 106.4406,
                'description' => 'Provinsi Kepulauan Bangka Belitung.'
            ],

            [
                'name' => 'Kepulauan Riau',
                'latitude' => 3.9457,
                'longitude' => 108.1429,
                'description' => 'Provinsi Kepulauan Riau.'
            ],

            [
                'name' => 'DKI Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'description' => 'Daerah Khusus Ibukota Jakarta.'
            ],

            [
                'name' => 'Jawa Barat',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'description' => 'Provinsi Jawa Barat.'
            ],

            [
                'name' => 'Jawa Tengah',
                'latitude' => -7.1509,
                'longitude' => 110.1403,
                'description' => 'Provinsi Jawa Tengah.'
            ],

            [
                'name' => 'DI Yogyakarta',
                'latitude' => -7.7956,
                'longitude' => 110.3695,
                'description' => 'Daerah Istimewa Yogyakarta.'
            ],

            [
                'name' => 'Jawa Timur',
                'latitude' => -7.5361,
                'longitude' => 112.2384,
                'description' => 'Provinsi Jawa Timur.'
            ],

            [
                'name' => 'Banten',
                'latitude' => -6.4058,
                'longitude' => 106.0640,
                'description' => 'Provinsi Banten.'
            ],

            [
                'name' => 'Bali',
                'latitude' => -8.4095,
                'longitude' => 115.1889,
                'description' => 'Provinsi Bali.'
            ],

            [
                'name' => 'Nusa Tenggara Barat',
                'latitude' => -8.6529,
                'longitude' => 117.3616,
                'description' => 'Provinsi Nusa Tenggara Barat.'
            ],

            [
                'name' => 'Nusa Tenggara Timur',
                'latitude' => -8.6574,
                'longitude' => 121.0794,
                'description' => 'Provinsi Nusa Tenggara Timur.'
            ],

            [
                'name' => 'Kalimantan Barat',
                'latitude' => -0.2788,
                'longitude' => 111.4753,
                'description' => 'Provinsi Kalimantan Barat.'
            ],

            [
                'name' => 'Kalimantan Tengah',
                'latitude' => -1.6815,
                'longitude' => 113.3824,
                'description' => 'Provinsi Kalimantan Tengah.'
            ],

            [
                'name' => 'Kalimantan Selatan',
                'latitude' => -3.0926,
                'longitude' => 115.2838,
                'description' => 'Provinsi Kalimantan Selatan.'
            ],

            [
                'name' => 'Kalimantan Timur',
                'latitude' => 0.5387,
                'longitude' => 116.4194,
                'description' => 'Provinsi Kalimantan Timur.'
            ],

            [
                'name' => 'Kalimantan Utara',
                'latitude' => 3.0731,
                'longitude' => 116.0414,
                'description' => 'Provinsi Kalimantan Utara.'
            ],

            [
                'name' => 'Sulawesi Utara',
                'latitude' => 0.6247,
                'longitude' => 123.9750,
                'description' => 'Provinsi Sulawesi Utara.'
            ],

            [
                'name' => 'Sulawesi Tengah',
                'latitude' => -1.4300,
                'longitude' => 121.4456,
                'description' => 'Provinsi Sulawesi Tengah.'
            ],

            [
                'name' => 'Sulawesi Selatan',
                'latitude' => -3.6688,
                'longitude' => 119.9741,
                'description' => 'Provinsi Sulawesi Selatan.'
            ],

            [
                'name' => 'Sulawesi Tenggara',
                'latitude' => -4.1449,
                'longitude' => 122.1746,
                'description' => 'Provinsi Sulawesi Tenggara.'
            ],

            [
                'name' => 'Gorontalo',
                'latitude' => 0.6999,
                'longitude' => 122.4467,
                'description' => 'Provinsi Gorontalo.'
            ],

            [
                'name' => 'Sulawesi Barat',
                'latitude' => -2.8441,
                'longitude' => 119.2321,
                'description' => 'Provinsi Sulawesi Barat.'
            ],

            [
                'name' => 'Maluku',
                'latitude' => -3.2385,
                'longitude' => 130.1453,
                'description' => 'Provinsi Maluku.'
            ],

            [
                'name' => 'Maluku Utara',
                'latitude' => 1.5709,
                'longitude' => 127.8088,
                'description' => 'Provinsi Maluku Utara.'
            ],

            [
                'name' => 'Papua Barat',
                'latitude' => -1.3361,
                'longitude' => 133.1747,
                'description' => 'Provinsi Papua Barat.'
            ],

            [
                'name' => 'Papua',
                'latitude' => -2.5337,
                'longitude' => 140.7181,
                'description' => 'Provinsi Papua.'
            ],

            [
                'name' => 'Papua Selatan',
                'latitude' => -7.0000,
                'longitude' => 139.5000,
                'description' => 'Provinsi Papua Selatan.'
            ],

            [
                'name' => 'Papua Tengah',
                'latitude' => -4.0000,
                'longitude' => 136.0000,
                'description' => 'Provinsi Papua Tengah.'
            ],

            [
                'name' => 'Papua Pegunungan',
                'latitude' => -4.0833,
                'longitude' => 138.9500,
                'description' => 'Provinsi Papua Pegunungan.'
            ],

            [
                'name' => 'Papua Barat Daya',
                'latitude' => -1.3000,
                'longitude' => 131.5000,
                'description' => 'Provinsi Papua Barat Daya.'
            ],

        ];

        foreach ($provinces as $province) {

            Province::updateOrCreate(
                [
                    'slug' => Str::slug($province['name'])
                ],
                [
                    'name' => $province['name'],
                    'latitude' => $province['latitude'],
                    'longitude' => $province['longitude'],
                    'description' => $province['description'],
                ]
            );
        }
    }
}