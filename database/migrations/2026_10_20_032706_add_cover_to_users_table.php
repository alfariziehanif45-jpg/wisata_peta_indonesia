<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cover_type', 20)->default('default')->after('avatar_path'); // default|gradient|image|video
            $table->string('cover_path')->nullable()->after('cover_type');
            $table->string('cover_color', 7)->nullable()->after('cover_path');
            $table->string('cover_color2', 7)->nullable()->after('cover_color');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cover_type', 'cover_path', 'cover_color', 'cover_color2']);
        });
    }
};