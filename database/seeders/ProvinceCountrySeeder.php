<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProvinceCountrySeeder extends Seeder
{
    public function run(): void
    {
        $indonesia = Country::where('code', 'ID')->first();

        if (!$indonesia) {
            return;
        }

        // Daftar nama provinsi Indonesia yang dianggap valid untuk
        // hubungan provinces -> countries. Data negara lain dibiarkan
        // tanpa country_id agar tidak muncul di mode Indonesia.
        $indonesiaProvinceNames = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Kepulauan Riau',
            'Jambi',
            'Sumatera Selatan',
            'Kepulauan Bangka Belitung',
            'Bengkulu',
            'Lampung',
            'DKI Jakarta',
            'Daerah Khusus Ibukota Jakarta',
            'Jakarta',
            'Jawa Barat',
            'Banten',
            'Jawa Tengah',
            'Daerah Istimewa Yogyakarta',
            'DI Yogyakarta',
            'Yogyakarta',
            'Jawa Timur',
            'Bali',
            'Nusa Tenggara Barat',
            'Nusa Tenggara Timur',
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat',
            'Maluku',
            'Maluku Utara',
            'Papua',
            'Papua Barat',
            'Papua Selatan',
            'Papua Tengah',
            'Papua Pegunungan',
            'Papua Barat Daya',
        ];

        $normalise = static function (string $value): string {
            $value = Str::ascii($value);
            $value = Str::lower(trim($value));
            return preg_replace('/[^a-z0-9]+/', '', $value) ?? '';
        };

        $valid = [];
        foreach ($indonesiaProvinceNames as $name) {
            $valid[$normalise($name)] = true;
        }

        // Reset seluruh mapping agar data asing yang sebelumnya salah
        // ditandai sebagai Indonesia tidak ikut tampil.
        Province::query()->update([
            'country_id' => null,
        ]);

        foreach (Province::all() as $province) {
            if (isset($valid[$normalise((string) $province->name)])) {
                $province->country_id = $indonesia->id;
                $province->type = $province->type ?: 'Province';
                $province->save();
            }
        }
    }
}
