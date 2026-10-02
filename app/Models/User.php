<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PasswordResetNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'fullname',
    'email',
    'password',
    'cin',
    'phone',
    'birthdate',
    'governorate',
    'city',
    'address',
])]



#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PasswordResetNotification($token));
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function productionLocation(): HasOne
    {
        return $this->hasOne(Location::class)->where('type', 'PRODUCTION_SITE');
    }

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
            'birthdate' => 'date',
        ];
    }
}