<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'user:make-admin {email : Email user yang akan dijadikan admin}';

    protected $description = 'Jadikan seorang user sebagai admin';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('User dengan email tersebut tidak ditemukan.');

            return self::FAILURE;
        }

        // forceFill: kolom "role" sengaja tidak dimasukkan ke $fillable
        // supaya tidak bisa diubah lewat form (mass assignment).
        $user->forceFill(['role' => 'admin'])->save();

        $this->info("{$user->name} ({$user->email}) sekarang adalah admin.");

        return self::SUCCESS;
    }
}