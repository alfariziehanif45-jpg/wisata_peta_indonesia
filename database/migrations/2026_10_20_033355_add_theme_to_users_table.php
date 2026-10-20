<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name_color', 7)->nullable();
            $table->string('username_color', 7)->nullable();

            $table->string('bg_type', 20)->default('default'); // default|color|gradient|image
            $table->string('bg_color', 7)->nullable();
            $table->string('bg_color2', 7)->nullable();
            $table->string('bg_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name_color', 'username_color',
                'bg_type', 'bg_color', 'bg_color2', 'bg_path',
            ]);
        });
    }
};