<?php

namespace App\Jobs;

use App\Models\Lot;
use Illuminate\Concurrency\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessRecall implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(public Lot $lot)
    {
    }

    public function handle(): void
    {
        // Traitement du rappel produit.
    }
}
