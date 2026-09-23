<?php

namespace App\Jobs;

use App\Models\TraceEvent;
use Illuminate\Concurrency\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalyzeTraceabilityEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(public TraceEvent $traceEvent)
    {
    }

    public function handle(): void
    {
        // Analyse métier asynchrone exécutée en file.
    }
}
