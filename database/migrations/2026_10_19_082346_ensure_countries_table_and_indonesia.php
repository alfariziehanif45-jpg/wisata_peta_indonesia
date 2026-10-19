<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code', 2)->unique();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!DB::table('countries')->where('code', 'ID')->exists()) {
            DB::table('countries')->insert([
                'name' => 'Indonesia',
                'code' => 'ID',
                'latitude' => -2.5489,
                'longitude' => 118.0149,
                'description' => 'Negara Kesatuan Republik Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pulihkan relasi provinsi Indonesia jika migrasi lama pernah tercatat
        // selesai tetapi tabel/relasinya tidak lengkap.
        if (Schema::hasTable('provinces') && Schema::hasColumn('provinces', 'country_id')) {
            $indonesiaId = DB::table('countries')->where('code', 'ID')->value('id');
            $provinceNames = [
                'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau',
                'Jambi', 'Sumatera Selatan', 'Kepulauan Bangka Belitung', 'Bengkulu',
                'Lampung', 'DKI Jakarta', 'Daerah Khusus Ibukota Jakarta', 'Jakarta',
                'Daerah Khusus Jakarta', 'Jawa Barat', 'Banten', 'Jawa Tengah',
                'Daerah Istimewa Yogyakarta', 'DI Yogyakarta', 'Yogyakarta', 'Jawa Timur',
                'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat',
                'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
                'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
                'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat',
                'Papua Selatan', 'Papua Tengah', 'Papua Pegunungan', 'Papua Barat Daya',
            ];
            DB::table('provinces')->whereIn('name', $provinceNames)
                ->update(['country_id' => $indonesiaId]);
        }
    }

    public function down(): void
    {
        // Jangan menghapus tabel/negara karena mungkin sudah digunakan data wilayah.
    }
};