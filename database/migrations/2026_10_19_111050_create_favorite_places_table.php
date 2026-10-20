<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Kunci unik tempat (dibuat di peta dari place_id + koordinat)
            $table->string('place_key', 191);

            $table->string('name');
            $table->string('category', 20);              // wisata | kuliner
            $table->string('type')->nullable();
            $table->string('address', 500)->nullable();

            // Boleh kosong untuk favorit manual yang tidak punya koordinat
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->decimal('rating', 2, 1)->nullable();
            $table->unsignedInteger('user_rating_count')->default(0);
            $table->string('google_maps_url', 1000)->nullable();

            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();

            $table->timestamps();

            // Satu pengguna tidak bisa menyimpan tempat yang sama dua kali
            $table->unique(['user_id', 'place_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_places');
    }
};