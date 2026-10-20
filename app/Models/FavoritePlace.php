<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoritePlace extends Model
{
    protected $fillable = [
        'user_id',
        'place_key',
        'name',
        'category',
        'type',
        'address',
        'latitude',
        'longitude',
        'rating',
        'user_rating_count',
        'google_maps_url',
        'province',
        'city',
    ];

    protected $casts = [
        'latitude'          => 'float',
        'longitude'         => 'float',
        'rating'            => 'float',
        'user_rating_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}