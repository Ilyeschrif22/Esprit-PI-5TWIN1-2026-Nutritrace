<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColdChainLog extends Model
{
    protected $table = 'cold_chain_logs';

    protected $fillable = [
        'lot_id',
        'location_id',
        'action',
        'temperature_c',
        'occurred_at',
        'notes',
    ];

    protected $casts = [
        'temperature_c' => 'float',
        'occurred_at' => 'datetime',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
