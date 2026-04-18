<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceIdentity extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceIdentityFactory> */
    use HasFactory;
    protected $fillable = ['device_id', 'name', 'type', 'room', 'is_active', 'metadata'];

protected $casts = [
    'is_active' => 'boolean',
    'metadata' => 'array',
];

}
