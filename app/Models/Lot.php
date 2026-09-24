<?php

namespace App\Models;

use App\Enums\LotStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    protected $fillable = [
        'product_id',
        'lot_number',
        'status',
        'quantity',
        'unit',
        'origin',
        'location',
        'produced_at',
        'public_token',
        'metadata',
    ];

    protected $casts = [
        'status' => LotStatus::class,
        'quantity' => 'float',
        'produced_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(TraceEvent::class)->orderBy('occurred_at');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(TraceAlert::class)->orderBy('detected_at');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class)->orderBy('departed_at');
    }

    public function transformations(): HasMany
    {
        return $this->hasMany(Transformation::class, 'input_lot_id')->orderBy('occurred_at');
    }

    public function outputTransformations(): HasMany
    {
        return $this->hasMany(Transformation::class, 'output_lot_id')->orderBy('occurred_at');
    }

    public function coldChainLogs(): HasMany
    {
        return $this->hasMany(ColdChainLog::class)->orderBy('occurred_at');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'entity_id')->where('entity_type', self::class);
    }
}
