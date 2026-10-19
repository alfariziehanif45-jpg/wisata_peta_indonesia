<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('provinces', 'type')) {
            Schema::table('provinces', function (Blueprint $table) {
                $table->string('type')->default('Province')->after('country_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('provinces', 'type')) {
            Schema::table('provinces', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
