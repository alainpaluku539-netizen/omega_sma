<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'active', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Conversion des types de colonnes.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    /**
     * RELATION : Un utilisateur peut enregistrer plusieurs documents.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * HELPER : Vérifier si l'utilisateur est l'admin Omega.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->email === 'admin@omega.com';
    }

    /**
     * HELPER : Vérifier si l'utilisateur est actif.
     */
    public function isActive(): bool
    {
        return $this->active === true;
    }

    /**
     * GETTER : Récupérer l'URL de l'avatar ou une image par défaut.
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : 'https://ui-avatars.com' . urlencode($this->name) . '&color=22d3ee&background=0f172a';
    }
}
