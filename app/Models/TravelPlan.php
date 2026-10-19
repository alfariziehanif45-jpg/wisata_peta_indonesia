<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TravelPlan extends Model
{
    protected $fillable = ['user_id', 'title', 'notes', 'start_date', 'end_date', 'completed_at'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'completed_at' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function places(): HasMany { return $this->hasMany(TravelPlanPlace::class); }
    public function getProgressAttribute(): int
    {
        $total = $this->places_count ?? $this->places()->count();
        if ($total === 0) return 0;
        $visited = $this->visited_places_count ?? $this->places()->where('visited', true)->count();
        return (int) floor(($visited / $total) * 100);
    }
}
