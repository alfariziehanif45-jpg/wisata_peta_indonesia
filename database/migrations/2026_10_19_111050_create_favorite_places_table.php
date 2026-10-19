
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('favorite_places')) {
            Schema::create('favorite_places', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->string('place_key', 64);
                $table->string('name');
                $table->string('address')->nullable();
                $table->text('url')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'place_key']);
            });
        }
    }

    public function down(): void
    {
        // Tabel tidak dihapus agar data favorit tetap aman.
    }
};
