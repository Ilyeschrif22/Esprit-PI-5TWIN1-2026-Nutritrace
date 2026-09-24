<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'lot_id',
        'reference',
        'origin_location_id',
        'destination_location_id',
        'transport_mode',
        'carrier',
        'vehicle_reference',
        'status',
        'distance_km',
        'departed_at',
        'expected_arrival_at',
        'arrived_at',
        'metadata',
    ];

    protected $casts = [
        'distance_km' => 'float',
        'departed_at' => 'datetime',
        'expected_arrival_at' => 'datetime',
        'arrived_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }
}
