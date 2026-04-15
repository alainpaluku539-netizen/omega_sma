<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyLog extends Model
{
    /** @use HasFactory<\Database\Factories\EnergyLogFactory> */
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'usage_kw',
        'recorded_at',
    ];

    /**
     * Cast des attributs.
     * On s'assure que usage_kw est un nombre flottant et recorded_at une date.
     */
    protected $casts = [
        'usage_kw' => 'float',
        'recorded_at' => 'datetime',
    ];
}
