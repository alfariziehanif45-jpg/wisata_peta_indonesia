<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeOwner extends Command
{
    protected $signature = 'user:make-owner {email : Email user yang akan dijadikan owner}';

    protected $description = 'Jadikan seorang user sebagai Owner (hak akses penuh untuk kelola admin dan role)';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('User dengan email tersebut tidak ditemukan.');

            return self::FAILURE;
        }

        $user->forceFill(['role' => 'owner'])->save();

        $this->info("{$user->name} ({$user->email}) sekarang adalah OWNER dengan hak akses penuh.");

        return self::SUCCESS;
    }
}
