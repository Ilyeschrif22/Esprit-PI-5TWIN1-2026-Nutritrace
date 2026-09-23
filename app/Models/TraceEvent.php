<?php

namespace App\Models;

use App\Enums\TraceEventStatus;
use App\Enums\TraceStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TraceEvent extends Model
{
    protected $fillable = [
        'lot_id',
        'actor_id',
        'stage',
        'event_type',
        'occurred_at',
        'source_type',
        'source_id',
        'destination_type',
        'destination_id',
        'quantity',
        'unit',
        'latitude',
        'longitude',
        'metadata',
        'status',
    ];

    protected $casts = [
        'stage' => TraceStage::class,
        'status' => TraceEventStatus::class,
        'occurred_at' => 'datetime',
        'quantity' => 'float',
        'metadata' => 'array',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function destination(): MorphTo
    {
        return $this->morphTo();
    }
}
