<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';

    protected $fillable = [
        'device_id',
        'name',
        'type',
        'room',
        'location',
        'value',
        'color',
        'is_on',
        'status',
        'last_seen',
    ];

    protected $casts = [
        'value' => 'float',
        'is_on' => 'boolean',
        'last_seen' => 'datetime',
    ];

    /*
    |--------------------------------------------------
    | SCOPES
    |--------------------------------------------------
    */

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRoom($query, $room)
    {
        return $query->where('room', $room);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_seen', 'desc');
    }

    /*
    |--------------------------------------------------
    | ATTRIBUTS CALCULÉS
    |--------------------------------------------------
    */

    // Vérifie si le device est réellement actif (timeout 60s)
    public function getIsActiveAttribute()
    {
        if (!$this->last_seen) return false;

        return $this->last_seen->gt(now()->subSeconds(60));
    }

    // Statut visuel automatique
    public function getComputedStatusAttribute()
    {
        return $this->is_active ? 'online' : 'offline';
    }

    /*
    |--------------------------------------------------
    | ACTIONS
    |--------------------------------------------------
    */

    public function turnOn()
    {
        $this->update(['is_on' => true]);
    }

    public function turnOff()
    {
        $this->update(['is_on' => false]);
    }

    public function toggle()
    {
        $this->update(['is_on' => !$this->is_on]);
    }

    /*
    |--------------------------------------------------
    | HELPERS
    |--------------------------------------------------
    */

    public function isLight()
    {
        return $this->type === 'light';
    }

    public function isSensor()
    {
        return in_array($this->type, ['temperature', 'humidity', 'energy']);
    }
}