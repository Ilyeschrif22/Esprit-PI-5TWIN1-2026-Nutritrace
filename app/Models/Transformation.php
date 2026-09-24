<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transformation extends Model
{
    protected $fillable = [
        'input_lot_id',
        'output_lot_id',
        'process_name',
        'input_quantity',
        'output_quantity',
        'loss_quantity',
        'location_id',
        'occurred_at',
        'notes',
    ];

    protected $casts = [
        'input_quantity' => 'float',
        'output_quantity' => 'float',
        'loss_quantity' => 'float',
        'occurred_at' => 'datetime',
    ];

    public function inputLot(): BelongsTo
    {
        return $this->belongsTo(Lot::class, 'input_lot_id');
    }

    public function outputLot(): BelongsTo
    {
        return $this->belongsTo(Lot::class, 'output_lot_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
