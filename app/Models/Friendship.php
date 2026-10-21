<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    protected $fillable = ['requester_id', 'addressee_id', 'status'];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function addressee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addressee_id');
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /** Pengguna di sisi lain dari sudut pandang $user. */
    public function otherUser(User $user): User
    {
        return $this->requester_id === $user->id ? $this->addressee : $this->requester;
    }
}