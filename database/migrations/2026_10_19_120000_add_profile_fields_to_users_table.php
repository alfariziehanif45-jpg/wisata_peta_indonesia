<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'username' => fn (Blueprint $table) => $table->string('username')->nullable()->unique(),
            'location' => fn (Blueprint $table) => $table->string('location')->nullable(),
            'bio' => fn (Blueprint $table) => $table->text('bio')->nullable(),
            'avatar_path' => fn (Blueprint $table) => $table->string('avatar_path')->nullable(),
        ];

        foreach ($columns as $name => $addColumn) {
            if (!Schema::hasColumn('users', $name)) {
                Schema::table('users', function (Blueprint $table) use ($addColumn) {
                    $addColumn($table);
                });
            }
        }
    }

    public function down(): void
    {
        // Tidak menghapus kolom profil agar data pengguna tetap aman.
    }
};
