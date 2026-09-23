<?php

namespace App\Listeners;

use App\Events\TraceEventCreated;
use App\Services\TraceabilityService;

class DetectTraceabilityAnomaly
{
    public function __construct(protected TraceabilityService $traceabilityService)
    {
    }

    public function handle(TraceEventCreated $event): void
    {
        $this->traceabilityService->detectAnomalies($event->traceEvent->lot);
    }
}
