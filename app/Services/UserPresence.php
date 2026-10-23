<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Membaca status login user dari tabel "sessions"
 * (butuh SESSION_DRIVER=database).
 *
 *  - online  : aktivitas terakhir <= ONLINE_MINUTES menit lalu
 *  - idle    : session masih berlaku, tapi sudah lama tidak ada aktivitas
 *  - offline : tidak punya session aktif (sudah logout / session kedaluwarsa)
 */
class UserPresence
{
    public const ONLINE_MINUTES = 5;

    /** Batas waktu session dianggap masih berlaku (config/session.php -> lifetime). */
    public function loginThreshold(): int
    {
        return now()->subMinutes((int) config('session.lifetime', 120))->timestamp;
    }

    public function onlineThreshold(): int
    {
        return now()->subMinutes(self::ONLINE_MINUTES)->timestamp;
    }

    /** ID semua user yang sedang login (session masih berlaku). */
    public function loggedInUserIds(): Collection
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $this->loginThreshold())
            ->distinct()
            ->pluck('user_id');
    }

    /** ID user yang benar-benar aktif beberapa menit terakhir. */
    public function onlineUserIds(): Collection
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $this->onlineThreshold())
            ->distinct()
            ->pluck('user_id');
    }

    /**
     * Aktivitas terakhir per user: [user_id => timestamp].
     *
     * @param  array|Collection  $userIds
     */
    public function lastActivity($userIds): array
    {
        return DB::table('sessions')
            ->whereIn('user_id', $userIds)
            ->selectRaw('user_id, MAX(last_activity) as last_activity')
            ->groupBy('user_id')
            ->get()
            ->pluck('last_activity', 'user_id')
            ->all();
    }

    /** Ubah timestamp aktivitas terakhir menjadi: online | idle | offline. */
    public function status($lastActivity): string
    {
        $lastActivity = (int) $lastActivity;

        if ($lastActivity < $this->loginThreshold()) {
            return 'offline';
        }

        return $lastActivity >= $this->onlineThreshold() ? 'online' : 'idle';
    }
}