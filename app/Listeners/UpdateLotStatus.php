<?php

namespace App\Listeners;

use App\Enums\LotStatus;
use App\Enums\TraceStage;
use App\Events\TraceEventCreated;

class UpdateLotStatus
{
    public function handle(TraceEventCreated $event): void
    {
        $lot = $event->traceEvent->lot;

        if ($event->traceEvent->stage === TraceStage::PRODUCTION) {
            $lot->status = LotStatus::ACTIVE;
            $lot->save();

            return;
        }

        if ($event->traceEvent->stage === TraceStage::TRANSPORT) {
            $lot->status = LotStatus::IN_TRANSIT;
            $lot->save();
        }
    }
}
