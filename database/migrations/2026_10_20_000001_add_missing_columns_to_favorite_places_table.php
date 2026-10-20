<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Melengkapi tabel favorite_places yang sudah ada.
 * Hanya menambahkan kolom yang BELUM ada, jadi aman dijalankan
 * walaupun sebagian kolom sudah tersedia.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Jika tabel belum ada sama sekali, buat lengkap.
        if (! Schema::hasTable('favorite_places')) {
            Schema::create('favorite_places', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('place_key', 191);
                $table->string('name');
                $table->string('category', 20);
                $table->string('type')->nullable();
                $table->string('address', 500)->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->decimal('rating', 2, 1)->nullable();
                $table->unsignedInteger('user_rating_count')->default(0);
                $table->string('google_maps_url', 1000)->nullable();
                $table->string('province', 100)->nullable();
                $table->string('city', 100)->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'place_key']);
            });

            return;
        }

        // Tabel sudah ada -> tambahkan kolom yang kurang saja.
        $missing = fn (string $column) => ! Schema::hasColumn('favorite_places', $column);

        Schema::table('favorite_places', function (Blueprint $table) use ($missing) {
            if ($missing('place_key')) {
                $table->string('place_key', 191)->nullable();
            }
            if ($missing('category')) {
                $table->string('category', 20)->default('wisata');
            }
            if ($missing('type')) {
                $table->string('type')->nullable();
            }
            if ($missing('address')) {
                $table->string('address', 500)->nullable();
            }
            if ($missing('latitude')) {
                $table->decimal('latitude', 10, 7)->nullable();
            }
            if ($missing('longitude')) {
                $table->decimal('longitude', 10, 7)->nullable();
            }
            if ($missing('rating')) {
                $table->decimal('rating', 2, 1)->nullable();
            }
            if ($missing('user_rating_count')) {
                $table->unsignedInteger('user_rating_count')->default(0);
            }
            if ($missing('google_maps_url')) {
                $table->string('google_maps_url', 1000)->nullable();
            }
            if ($missing('province')) {
                $table->string('province', 100)->nullable();
            }
            if ($missing('city')) {
                $table->string('city', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        // Sengaja dikosongkan: migration ini hanya melengkapi tabel yang sudah ada,
        // sehingga tidak aman menghapus kolom secara otomatis.
    }
};