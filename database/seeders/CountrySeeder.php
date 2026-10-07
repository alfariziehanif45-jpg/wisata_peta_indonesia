<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        // Sistem proyek sekarang hanya menggunakan Indonesia.
        Province::query()->update(['country_id' => null]);
        Country::where('code', '!=', 'ID')->delete();

        Country::updateOrCreate(
            ['code' => 'ID'],
            [
                'name' => 'Indonesia',
                'latitude' => -2.5489,
                'longitude' => 118.0149,
            ]
        );
    }
}
