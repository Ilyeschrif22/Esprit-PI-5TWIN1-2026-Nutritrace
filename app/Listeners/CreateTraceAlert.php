<?php

namespace App\Listeners;

use App\Events\TraceEventCreated;
use App\Models\TraceAlert;

class CreateTraceAlert
{
    public function handle(TraceEventCreated $event): void
    {
        if ($event->traceEvent->lot->status === 'blocked') {
            TraceAlert::firstOrCreate(
                [
                    'trace_event_id' => $event->traceEvent->id,
                    'lot_id' => $event->traceEvent->lot_id,
                ],
                [
                    'type' => 'status',
                    'severity' => 'medium',
                    'message' => 'Le lot a été placé en statut bloqué.',
                    'status' => 'new',
                    'detected_at' => now(),
                    'metadata' => ['source' => 'TraceEventCreated'],
                ]
            );
        }
    }
}
