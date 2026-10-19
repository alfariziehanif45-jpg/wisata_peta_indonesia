<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'name',
        'type',
        'slug',
        'latitude',
        'longitude',
        'description',
    ];

    protected $appends = [
        'wikipedia_url',
        'wikipedia_label',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function getWikipediaUrlAttribute(): string
    {
        $name = trim($this->name ?? '');
        $type = trim($this->type ?: 'Kota');
        $prov = '';

        if ($this->relationLoaded('province') && $this->province) {
            $prov = $this->province->name ?? '';
        } elseif ($this->province_id) {
            $prov = optional($this->province)->name ?? '';
        }

        if (stripos($prov, 'jakarta') !== false) {
            if (strcasecmp($name, 'Kepulauan Seribu') === 0) {
                return 'https://id.wikipedia.org/wiki/Kabupaten_Administrasi_Kepulauan_Seribu';
            }
            return 'https://id.wikipedia.org/wiki/Kota_Administrasi_' . str_replace(' ', '_', $name);
        }

        if (stripos($name, 'kabupaten ') === 0 || stripos($name, 'kota ') === 0) {
            return 'https://id.wikipedia.org/wiki/' . str_replace(' ', '_', $name);
        }

        $prefix = (strcasecmp($type, 'Kota') === 0) ? 'Kota' : 'Kabupaten';
        return 'https://id.wikipedia.org/wiki/' . $prefix . '_' . str_replace(' ', '_', $name);
    }

    public function getWikipediaLabelAttribute(): string
    {
        $name = trim($this->name ?? '');
        $type = trim($this->type ?: 'Kota');
        $prov = '';

        if ($this->relationLoaded('province') && $this->province) {
            $prov = $this->province->name ?? '';
        } elseif ($this->province_id) {
            $prov = optional($this->province)->name ?? '';
        }

        if (stripos($prov, 'jakarta') !== false) {
            if (strcasecmp($name, 'Kepulauan Seribu') === 0) {
                return 'Kabupaten Administrasi Kepulauan Seribu';
            }
            return 'Kota Administrasi ' . $name;
        }

        if (stripos($name, 'kabupaten ') === 0 || stripos($name, 'kota ') === 0) {
            return $name;
        }

        $prefix = (strcasecmp($type, 'Kota') === 0) ? 'Kota' : 'Kabupaten';
        return $prefix . ' ' . $name;
    }
}
