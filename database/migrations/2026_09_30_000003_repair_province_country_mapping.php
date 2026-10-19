<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'name' => 'Indonesia', 'code' => 'ID',
                'latitude' => -2.5489, 'longitude' => 118.0149,
                'description' => null, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $indonesiaId = DB::table('countries')
            ->where('code', 'ID')
            ->value('id');

        if (!$indonesiaId) {
            return;
        }

        $validNames = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau',
            'Kepulauan Riau', 'Jambi', 'Sumatera Selatan',
            'Kepulauan Bangka Belitung', 'Bengkulu', 'Lampung',
            'DKI Jakarta', 'Daerah Khusus Ibukota Jakarta', 'Jakarta',
            'Jawa Barat', 'Banten', 'Jawa Tengah', 'Daerah Istimewa Yogyakarta',
            'DI Yogyakarta', 'Yogyakarta', 'Jawa Timur', 'Bali',
            'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
            'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan',
            'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara',
            'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara',
            'Papua', 'Papua Barat', 'Papua Selatan', 'Papua Tengah',
            'Papua Pegunungan', 'Papua Barat Daya',
        ];

        $normalise = static function (string $value): string {
            $value = Str::ascii($value);
            $value = Str::lower(trim($value));
            return preg_replace('/[^a-z0-9]+/', '', $value) ?? '';
        };

        $valid = [];
        foreach ($validNames as $name) {
            $valid[$normalise($name)] = true;
        }

        DB::table('provinces')->update(['country_id' => null]);

        foreach (DB::table('provinces')->select('id', 'name')->get() as $province) {
            if (isset($valid[$normalise((string) $province->name)])) {
                DB::table('provinces')
                    ->where('id', $province->id)
                    ->update(['country_id' => $indonesiaId]);
            }
        }
    }

    public function down(): void
    {
        DB::table('provinces')->update(['country_id' => null]);
    }
};
