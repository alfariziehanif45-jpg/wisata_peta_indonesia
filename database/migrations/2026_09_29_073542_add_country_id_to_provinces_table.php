<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('provinces', 'country_id')) {
            Schema::table('provinces', function (Blueprint $table) {
                $table->foreignId('country_id')->nullable()->after('id')
                    ->constrained('countries')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('provinces', 'country_id')) {
            Schema::table('provinces', function (Blueprint $table) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            });
        }
    }
};
