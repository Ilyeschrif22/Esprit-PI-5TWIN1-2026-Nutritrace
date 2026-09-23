<?php

namespace App\Actions;

use App\Models\Lot;
use App\Models\User;
use App\Services\TraceabilityService;

class CreateProductionTrace
{
    public function __construct(protected TraceabilityService $traceabilityService)
    {
    }

    public function handle(array $data, User $actor): Lot
    {
        return $this->traceabilityService->recordProduction($data, $actor);
    }
}
