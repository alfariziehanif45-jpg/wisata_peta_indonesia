<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cover_type')) {
                $table->string('cover_type')->nullable()->after('cover_path');
            }
            if (!Schema::hasColumn('users', 'cover_color')) {
                $table->string('cover_color')->nullable()->after('cover_type');
            }
            if (!Schema::hasColumn('users', 'cover_color2')) {
                $table->string('cover_color2')->nullable()->after('cover_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cover_type', 'cover_color', 'cover_color2']);
        });
    }
};