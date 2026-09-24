<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $wisata = Category::where('slug', 'wisata')->first();
        $kuliner = Category::where('slug', 'kuliner')->first();
        $budaya = Category::where('slug', 'budaya')->first();

        $palembang = City::where('slug', 'palembang')->first();

        Destination::create([
            'city_id' => $palembang->id,
            'category_id' => $wisata->id,
            'name' => 'Jembatan Ampera',
            'slug' => 'jembatan-ampera',
            'description' => 'Ikon Kota Palembang yang berada di atas Sungai Musi.',
            'latitude' => -2.9911,
            'longitude' => 104.7754,
            'address' => 'Palembang, Sumatera Selatan',
        ]);

        Destination::create([
            'city_id' => $palembang->id,
            'category_id' => $kuliner->id,
            'name' => 'Pempek Palembang',
            'slug' => 'pempek-palembang',
            'description' => 'Makanan khas Palembang berbahan dasar ikan dan tepung sagu.',
            'latitude' => -2.9909,
            'longitude' => 104.7754,
            'address' => 'Palembang, Sumatera Selatan',
        ]);

        $bandung = City::where('slug', 'bandung')->first();

        Destination::create([
            'city_id' => $bandung->id,
            'category_id' => $wisata->id,
            'name' => 'Gedung Sate',
            'slug' => 'gedung-sate',
            'description' => 'Bangunan ikonik dan salah satu landmark Kota Bandung.',
            'latitude' => -6.9025,
            'longitude' => 107.6188,
            'address' => 'Bandung, Jawa Barat',
        ]);

        $denpasar = City::where('slug', 'denpasar')->first();

        Destination::create([
            'city_id' => $denpasar->id,
            'category_id' => $budaya->id,
            'name' => 'Pura Jagatnatha',
            'slug' => 'pura-jagatnatha',
            'description' => 'Pura yang berada di pusat Kota Denpasar.',
            'latitude' => -8.6550,
            'longitude' => 115.2167,
            'address' => 'Denpasar, Bali',
        ]);
    }
}