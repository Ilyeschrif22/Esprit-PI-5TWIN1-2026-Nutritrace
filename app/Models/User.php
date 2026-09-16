<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'prenom',
    'email',
    'password',
    'cin',
    'telephone',
    'date_naissance',
    'genre',
    'gouvernorat',
    'delegation',
    'ville',
    'adresse',
    'code_postal',
])]



#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
        ];
    }

    /**
     * Full name for a Tunisian profile.
     */
    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->name}");
    }
}
