<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) $table->string('username', 30)->nullable()->unique();
            if (!Schema::hasColumn('users', 'domicile')) $table->string('domicile', 100)->nullable();
            if (!Schema::hasColumn('users', 'bio')) $table->text('bio')->nullable();
            if (!Schema::hasColumn('users', 'avatar_path')) $table->string('avatar_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) { $table->dropUnique(['username']); $table->dropColumn('username'); }
            foreach (['domicile', 'bio', 'avatar_path'] as $column) {
                if (Schema::hasColumn('users', $column)) $table->dropColumn($column);
            }
        });
    }
};
