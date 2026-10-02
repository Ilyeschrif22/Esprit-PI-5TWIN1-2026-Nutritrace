<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'production_type',
        'production_method',
        'production_reference',
        'address',
        'city',
        'governorate',
        'latitude',
        'longitude',
        'metadata',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'metadata' => 'array',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function originShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'origin_location_id');
    }

    public function destinationShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'destination_location_id');
    }
}
