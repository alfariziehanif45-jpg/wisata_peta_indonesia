<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->nullable()->unique()->after('name');
            $table->string('domicile', 120)->nullable()->after('email');
            $table->string('gender', 40)->nullable()->after('domicile');
            $table->date('birth_date')->nullable()->after('gender');
            $table->text('bio')->nullable()->after('birth_date');
            $table->string('avatar_path')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'domicile', 'gender', 'birth_date', 'bio', 'avatar_path']);
        });
    }
};
