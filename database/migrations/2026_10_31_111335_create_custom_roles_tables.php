<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
            $table->string('icon', 16)->nullable();          // emoji, mis. 👑
            $table->string('color', 7)->default('#e11d48');  // hex
            $table->string('description', 160)->nullable();
            $table->timestamps();
        });

        Schema::create('custom_role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('custom_role_id')->constrained('custom_roles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'custom_role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_role_user');
        Schema::dropIfExists('custom_roles');
    }
};