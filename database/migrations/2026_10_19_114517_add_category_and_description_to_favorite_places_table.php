
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorite_places', function (Blueprint $table) {
            if (!Schema::hasColumn('favorite_places', 'category')) {
                $table->string('category', 100)
                    ->default('Kuliner');
            }

            if (!Schema::hasColumn('favorite_places', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('favorite_places', function (Blueprint $table) {
            if (Schema::hasColumn('favorite_places', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('favorite_places', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
