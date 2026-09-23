<?php

namespace App\Events;

use App\Models\TraceEvent;
use Illuminate\Foundation\Events\Dispatchable;

class TraceEventCreated
{
    use Dispatchable;

    public function __construct(public TraceEvent $traceEvent)
    {
    }
}
