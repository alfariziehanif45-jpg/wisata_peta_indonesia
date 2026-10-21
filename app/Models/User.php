<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasFriends;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasFriends;

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
}