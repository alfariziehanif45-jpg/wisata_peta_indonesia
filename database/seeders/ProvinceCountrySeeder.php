<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;

class ProvinceCountrySeeder extends Seeder
{
    public function run(): void
    {
        $indonesia = Country::where('code', 'ID')->first();
        if ($indonesia) {
            Province::query()->update(['country_id' => $indonesia->id]);
        }
    }
}
