<?php

namespace App\Models;

use App\Enums\TraceAlertSeverity;
use App\Enums\TraceAlertType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraceAlert extends Model
{
    protected $fillable = [
        'trace_event_id',
        'lot_id',
        'type',
        'severity',
        'message',
        'status',
        'detected_at',
        'resolved_at',
        'resolved_by',
        'metadata',
    ];

    protected $casts = [
        'type' => TraceAlertType::class,
        'severity' => TraceAlertSeverity::class,
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(TraceEvent::class, 'trace_event_id');
    }
}
