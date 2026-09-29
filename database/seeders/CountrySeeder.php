<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Brunei Darussalam', 'code' => 'BN', 'latitude' => 4.5353, 'longitude' => 114.7277],
            ['name' => 'Cambodia', 'code' => 'KH', 'latitude' => 12.5657, 'longitude' => 104.9910],
            ['name' => 'Indonesia', 'code' => 'ID', 'latitude' => -2.5489, 'longitude' => 118.0149],
            ['name' => 'Lao PDR', 'code' => 'LA', 'latitude' => 19.8563, 'longitude' => 102.4955],
            ['name' => 'Malaysia', 'code' => 'MY', 'latitude' => 4.2105, 'longitude' => 101.9758],
            ['name' => 'Myanmar', 'code' => 'MM', 'latitude' => 21.9162, 'longitude' => 95.9560],
            ['name' => 'Philippines', 'code' => 'PH', 'latitude' => 12.8797, 'longitude' => 121.7740],
            ['name' => 'Singapore', 'code' => 'SG', 'latitude' => 1.3521, 'longitude' => 103.8198],
            ['name' => 'Thailand', 'code' => 'TH', 'latitude' => 15.8700, 'longitude' => 100.9925],
            ['name' => 'Timor-Leste', 'code' => 'TL', 'latitude' => -8.8742, 'longitude' => 125.7275],
            ['name' => 'Viet Nam', 'code' => 'VN', 'latitude' => 14.0583, 'longitude' => 108.2772],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], $country);
        }
    }
}
