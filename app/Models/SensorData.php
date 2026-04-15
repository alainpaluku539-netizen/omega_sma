<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SensorData extends Model
{
    use HasFactory;

    protected $table = 'sensor_data';

    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'pressure',
        'rssi',
        'status',
        'sensor_type',
        'measured_at',
    ];
}