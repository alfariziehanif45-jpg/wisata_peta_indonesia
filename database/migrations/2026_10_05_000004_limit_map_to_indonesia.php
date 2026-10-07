<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $indonesia = DB::table('countries')->where('code', 'ID')->first();

        if (!$indonesia) {
            DB::table('countries')->insert([
                'name' => 'Indonesia',
                'code' => 'ID',
                'latitude' => -2.5489,
                'longitude' => 118.0149,
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $indonesia = DB::table('countries')->where('code', 'ID')->first();
        }

        // Lepaskan relasi sementara agar penghapusan negara lain tidak
        // menghapus data provinsi yang sudah ada karena foreign key cascade.
        DB::table('provinces')->update(['country_id' => null]);
        DB::table('countries')->where('code', '!=', 'ID')->delete();

        $valid = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau',
            'Jambi', 'Sumatera Selatan', 'Kepulauan Bangka Belitung', 'Bengkulu',
            'Lampung', 'DKI Jakarta', 'Daerah Khusus Ibukota Jakarta', 'Jakarta',
            'Daerah Khusus Jakarta', 'Jawa Barat', 'Banten', 'Jawa Tengah',
            'Daerah Istimewa Yogyakarta', 'DI Yogyakarta', 'Yogyakarta', 'Jawa Timur',
            'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat',
            'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat',
            'Papua Selatan', 'Papua Tengah', 'Papua Pegunungan', 'Papua Barat Daya'
        ];

        foreach ($valid as $name) {
            DB::table('provinces')
                ->where('name', $name)
                ->update(['country_id' => $indonesia->id]);
        }
    }

    public function down(): void
    {
        // Tidak memulihkan negara ASEAN karena versi aplikasi ini
        // sengaja difokuskan hanya pada Indonesia.
    }
};
