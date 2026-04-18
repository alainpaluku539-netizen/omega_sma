<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les champs que l'on peut remplir massivement.
     */
    protected $fillable = [
        'reference',
        'entry_number',
        'entry_year',
        'title',
        'description',
        'doc_type',
        'direction',
        'origin_destination',
        'mention',
        'classification',
        'leader_decision',
        'leader_notes',
        'user_id',
        'action_date',
    ];

    /**
     * Conversion automatique des types.
     */
    protected $casts = [
        'action_date' => 'datetime',
        'entry_year'  => 'integer',
        'entry_number' => 'integer',
    ];

    /**
     * Relation : Le document appartient à un utilisateur (l'opérateur).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer facilement par direction (IN/OUT).
     */
    public function scopeIncoming($query)
    {
        return $query->where('direction', 'IN');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('direction', 'OUT');
    }
}
