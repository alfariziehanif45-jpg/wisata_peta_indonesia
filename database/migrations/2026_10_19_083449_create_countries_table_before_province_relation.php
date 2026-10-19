
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
                'description' => 'Negara Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Jangan hapus tabel negara yang mungkin sudah digunakan.
    }
};
