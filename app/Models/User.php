<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasFriends;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasFriends;

    /**
     * Daftar border foto profil yang valid.
     * Harus sama dengan kelas CSS .bd-* di halaman profil & edit profil.
     */
    public const AVATAR_BORDERS = [
        'default',
        'none',
        'emas',
        'pelangi',
        'neon',
        'api',
        'es',
        'galaksi',
        'hati',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * Kolom profil (username, bio, sampul, warna, background, dst.)
     * ditambahkan agar bisa disimpan lewat halaman Edit Profil.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',

        // Profil
        'username',
        'domicile',
        'bio',
        'avatar_path',

        // Border foto profil
        'avatar_border',

        // Sampul profil
        'cover_type',
        'cover_color',
        'cover_color2',
        'cover_path',

        // Warna tulisan
        'name_color',
        'username_color',

        // Background halaman profil
        'bg_type',
        'bg_color',
        'bg_color2',
        'bg_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Destinasi favorit milik pengguna
     * (dari tombol hati di peta maupun input manual).
     */
    public function favoritePlaces(): HasMany
    {
        return $this->hasMany(FavoritePlace::class);
    }

    /* ----------------------------- ADMIN ----------------------------- */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Rencana perjalanan milik pengguna. */
    public function travelPlans(): HasMany
    {
        return $this->hasMany(TravelPlan::class);
    }

    /** Hitung teman (status accepted) tanpa query berulang. */
    public function scopeWithFriendsCount(Builder $query): Builder
    {
        return $query->withCount([
            'sentRequests as sent_friends_count'         => fn ($q) => $q->where('status', 'accepted'),
            'receivedRequests as received_friends_count' => fn ($q) => $q->where('status', 'accepted'),
        ]);
    }

    public function getFriendsTotalAttribute(): int
    {
        return (int) ($this->sent_friends_count ?? 0) + (int) ($this->received_friends_count ?? 0);
    }
}