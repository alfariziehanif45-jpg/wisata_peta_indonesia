<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRole extends Model
{
    protected $fillable = ['name', 'icon', 'color', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'custom_role_user')->withTimestamps();
    }
}