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
            $table->string('place_key', 190);
            $table->string('name');
            $table->string('category', 30);
            $table->string('type')->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('rating', 2, 1)->nullable();
            $table->unsignedInteger('user_rating_count')->default(0);
            $table->text('google_maps_url')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'place_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_places');
    }
};