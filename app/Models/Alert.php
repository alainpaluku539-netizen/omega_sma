<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $table = 'alerts';

    protected $fillable = [
        'device_id',
        'type',        // ex: temperature, humidity, security
        'message',     // description de l’alerte
        'level',       // info, warning, critical
        'value',       // valeur qui a déclenché l’alerte
        'is_read',     // 0 = non lu, 1 = lu
    ];

    protected $casts = [
        'value' => 'float',
        'is_read' => 'boolean',
    ];

    /**
     * Scope: alertes non lues
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: par niveau d'alerte
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope: par device
     */
    public function scopeDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }
}