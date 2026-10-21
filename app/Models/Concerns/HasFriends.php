<?php

namespace App\Models\Concerns;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasFriends
{
    /** Permintaan yang DIKIRIM user ini. */
    public function sentRequests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    /** Permintaan yang DITERIMA user ini. */
    public function receivedRequests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    /** Semua pertemanan yang sudah diterima (dua arah). */
    public function acceptedFriendships(): Builder
    {
        return Friendship::query()
            ->where('status', 'accepted')
            ->where(fn ($q) => $q->where('requester_id', $this->id)->orWhere('addressee_id', $this->id));
    }

    public function friendIds()
    {
        return $this->acceptedFriendships()
            ->get(['requester_id', 'addressee_id'])
            ->map(fn ($f) => $f->requester_id === $this->id ? $f->addressee_id : $f->requester_id);
    }

    /** Query daftar teman (model User). */
    public function friends(): Builder
    {
        return User::query()->whereIn('id', $this->friendIds());
    }

    /** Hubungan antara user ini dan user lain (arah mana pun). */
    public function friendshipWith(User $other): ?Friendship
    {
        return Friendship::query()
            ->where(fn ($q) => $q->where('requester_id', $this->id)->where('addressee_id', $other->id))
            ->orWhere(fn ($q) => $q->where('requester_id', $other->id)->where('addressee_id', $this->id))
            ->first();
    }
}