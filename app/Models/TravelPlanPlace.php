<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelPlanPlace extends Model
{
    protected $fillable = ['travel_plan_id', 'place_key', 'name', 'address', 'url', 'visited', 'visited_at'];
    protected $casts = ['visited' => 'boolean', 'visited_at' => 'datetime'];
    public function plan(): BelongsTo { return $this->belongsTo(TravelPlan::class, 'travel_plan_id'); }
}
