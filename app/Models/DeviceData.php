<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceData extends Model
{
    use HasFactory;

    protected $table = 'device_data';

    protected $fillable = [
        'device_id',
        'type',
        'value',
        'unit',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    /**
     * Scope: filtrer par device
     */
    public function scopeDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    /**
     * Scope: filtrer par type (temp, humidity, etc.)
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: données récentes
     */
    public function scopeLatestData($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}