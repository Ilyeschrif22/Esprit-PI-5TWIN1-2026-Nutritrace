<?php

namespace App\Services;

use App\Enums\LotStatus;
use App\Enums\TraceEventStatus;
use App\Enums\TraceStage;
use App\Events\TraceEventCreated;
use App\Models\ColdChainLog;
use App\Models\Location;
use App\Models\Lot;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\TraceAlert;
use App\Models\TraceEvent;
use App\Models\Transformation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TraceabilityService
{
    public function recordProduction(array $data, User $actor): Lot
    {
        return DB::transaction(function () use ($data, $actor) {
            $product = Product::firstOrCreate([
                'sku' => $data['sku'] ?? Str::slug($data['product_name'] ?? 'product'),
            ], [
                'name' => $data['product_name'] ?? 'Produit',
                'category' => $data['category'] ?? 'Divers',
                'unit' => $data['unit'] ?? 'kg',
                'description' => $data['description'] ?? null,
                'is_active' => true,
            ]);

            $quantity = (float) ($data['quantity'] ?? 0);
            if ($quantity <= 0) {
                throw new \InvalidArgumentException('La quantité doit être strictement positive.');
            }

            $lot = Lot::create([
                'product_id' => $product->id,
                'lot_number' => $data['lot_number'] ?? 'LOT-'.strtoupper(Str::random(8)),
                'status' => LotStatus::ACTIVE,
                'quantity' => $quantity,
                'unit' => $data['unit'] ?? 'kg',
                'origin' => $data['origin'] ?? 'N/A',
                'location' => $data['location'] ?? 'N/A',
                'produced_at' => $data['produced_at'] ?? now(),
                'public_token' => $data['public_token'] ?? (string) Str::ulid(),
                'metadata' => $data['metadata'] ?? [],
            ]);

            $event = TraceEvent::create([
                'lot_id' => $lot->id,
                'actor_id' => $actor->id,
                'stage' => TraceStage::PRODUCTION,
                'event_type' => 'PRODUCTION',
                'occurred_at' => $lot->produced_at ?? now(),
                'quantity' => $quantity,
                'unit' => $lot->unit,
                'status' => TraceEventStatus::VALIDATED,
                'metadata' => [
                    'origin' => $lot->origin,
                    'location' => $lot->location,
                ],
            ]);

            event(new TraceEventCreated($event));

            return $lot->fresh();
        });
    }

    public function getTimeline(Lot $lot): array
    {
        return $lot->events()->with('actor')->get()->map(function (TraceEvent $event) {
            return [
                'id' => $event->id,
                'stage' => $event->stage?->value,
                'event_type' => $event->event_type,
                'occurred_at' => $event->occurred_at?->toDateTimeString(),
                'actor' => $event->actor?->fullname ?? $event->actor?->email ?? 'Inconnu',
                'quantity' => $event->quantity,
                'unit' => $event->unit,
                'status' => $event->status?->value,
                'metadata' => $event->metadata ?? [],
            ];
        })->all();
    }

    public function getUpstream(Lot $lot): array
    {
        return [
            'lot' => $lot->lot_number,
            'product' => $lot->product?->name,
            'events' => $this->getTimeline($lot),
        ];
    }

    public function getDownstream(Lot $lot): array
    {
        return [
            'lot' => $lot->lot_number,
            'status' => $lot->status?->value,
            'events' => $this->getTimeline($lot),
        ];
    }

    public function detectAnomalies(Lot $lot): void
    {
        $events = $lot->events()->get();

        foreach ($events as $event) {
            if ($event->quantity !== null && $event->quantity < 0) {
                TraceAlert::create([
                    'trace_event_id' => $event->id,
                    'lot_id' => $lot->id,
                    'type' => 'quantity',
                    'severity' => 'high',
                    'message' => 'Quantité négative détectée sur le lot.',
                    'status' => 'new',
                    'detected_at' => now(),
                    'metadata' => ['quantity' => $event->quantity],
                ]);
            }
        }
    }

    public function createLocation(array $data): Location
    {
        return Location::firstOrCreate([
            'name' => $data['name'] ?? 'Lieu inconnu',
            'city' => $data['city'] ?? 'Inconnu',
        ], [
            'type' => $data['type'] ?? 'OTHER',
            'address' => $data['address'] ?? null,
            'governorate' => $data['governorate'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'metadata' => $data['metadata'] ?? [],
        ]);
    }

    public function createShipment(array $data, User $actor): Shipment
    {
        $shipment = Shipment::create([
            'lot_id' => $data['lot_id'],
            'reference' => $data['reference'] ?? 'SHIP-'.strtoupper(Str::random(8)),
            'origin_location_id' => $data['origin_location_id'],
            'destination_location_id' => $data['destination_location_id'],
            'transport_mode' => $data['transport_mode'] ?? 'road',
            'carrier' => $data['carrier'] ?? null,
            'vehicle_reference' => $data['vehicle_reference'] ?? null,
            'status' => 'dispatched',
            'distance_km' => $data['distance_km'] ?? 0,
            'departed_at' => $data['departed_at'] ?? now(),
            'expected_arrival_at' => $data['expected_arrival_at'] ?? now()->addDay(),
            'arrived_at' => $data['arrived_at'] ?? null,
            'metadata' => $data['metadata'] ?? ['created_by' => $actor->id],
        ]);

        $lot = $shipment->lot;

        TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $actor->id,
            'stage' => TraceStage::TRANSPORT,
            'event_type' => 'DISPATCHED',
            'occurred_at' => $shipment->departed_at ?? now(),
            'quantity' => $lot->quantity,
            'unit' => $lot->unit,
            'status' => TraceEventStatus::VALIDATED,
            'metadata' => [
                'shipment_id' => $shipment->id,
                'origin_location_id' => $shipment->origin_location_id,
                'destination_location_id' => $shipment->destination_location_id,
                'transport_mode' => $shipment->transport_mode,
             ],
        ]);

        return $shipment->fresh();
    }

    public function getPublicTrace(string $token): array
    {
        $lot = Lot::where('public_token', $token)->with(['product', 'events.actor'])->firstOrFail();

        return [
            'lot_number' => $lot->lot_number,
            'status' => $lot->status?->value ?? $lot->status,
            'quantity' => (float) $lot->quantity,
            'unit' => $lot->unit,
            'origin' => $lot->origin,
            'location' => $lot->location,
            'produced_at' => $lot->produced_at?->toDateTimeString(),
            'product' => [
                'name' => $lot->product?->name,
                'category' => $lot->product?->category,
                'sku' => $lot->product?->sku,
            ],
            'events' => $this->getTimeline($lot),
        ];
    }

    public function createTransformation(array $data, User $actor): Transformation
    {
        return DB::transaction(function () use ($data, $actor) {
            $inputLot = Lot::findOrFail($data['input_lot_id']);
            $outputQuantity = (float) ($data['output_quantity'] ?? 0);
            $lossQuantity = (float) ($data['loss_quantity'] ?? max(0, $inputLot->quantity - $outputQuantity));

            if ($outputQuantity < 0 || $lossQuantity < 0) {
                throw new \InvalidArgumentException('Quantité de sortie ou de perte invalide.');
            }

            $outputLot = Lot::create([
                'product_id' => $inputLot->product_id,
                'lot_number' => $data['output_lot_number'] ?? 'LOT-'.strtoupper(Str::random(8)),
                'status' => LotStatus::ACTIVE,
                'quantity' => $outputQuantity,
                'unit' => $data['unit'] ?? $inputLot->unit,
                'origin' => $inputLot->origin,
                'location' => $data['location_name'] ?? $inputLot->location,
                'produced_at' => $data['occurred_at'] ?? now(),
                'public_token' => $data['public_token'] ?? (string) Str::ulid(),
                'metadata' => ['transformation_from' => $inputLot->id],
            ]);

            $transformation = Transformation::create([
                'input_lot_id' => $inputLot->id,
                'output_lot_id' => $outputLot->id,
                'process_name' => $data['process_name'] ?? 'Transformation',
                'input_quantity' => $inputLot->quantity,
                'output_quantity' => $outputQuantity,
                'loss_quantity' => $lossQuantity,
                'location_id' => $data['location_id'] ?? null,
                'occurred_at' => $data['occurred_at'] ?? now(),
                'notes' => $data['notes'] ?? null,
            ]);

            TraceEvent::create([
                'lot_id' => $inputLot->id,
                'actor_id' => $actor->id,
                'stage' => TraceStage::TRANSFORMATION,
                'event_type' => 'TRANSFORMATION',
                'occurred_at' => $transformation->occurred_at,
                'quantity' => $inputLot->quantity,
                'unit' => $inputLot->unit,
                'status' => TraceEventStatus::VALIDATED,
                'metadata' => ['transformation_id' => $transformation->id, 'output_lot_id' => $outputLot->id],
            ]);

            TraceEvent::create([
                'lot_id' => $outputLot->id,
                'actor_id' => $actor->id,
                'stage' => TraceStage::PRODUCTION,
                'event_type' => 'PRODUCTION',
                'occurred_at' => $transformation->occurred_at,
                'quantity' => $outputQuantity,
                'unit' => $outputLot->unit,
                'status' => TraceEventStatus::VALIDATED,
                'metadata' => ['source_lot_id' => $inputLot->id],
            ]);

            $inputLot->update(['status' => LotStatus::TRANSFORMED]);
            $outputLot->update(['status' => LotStatus::ACTIVE]);

            return $transformation->fresh(['inputLot', 'outputLot', 'location']);
        });
    }

    public function createColdChainLog(array $data, User $actor): ColdChainLog
    {
        $lot = Lot::findOrFail($data['lot_id']);

        $log = ColdChainLog::create([
            'lot_id' => $lot->id,
            'location_id' => $data['location_id'] ?? null,
            'action' => $data['action'] ?? 'TEMPERATURE_CHECKED',
            'temperature_c' => $data['temperature_c'] ?? null,
            'occurred_at' => $data['occurred_at'] ?? now(),
            'notes' => $data['notes'] ?? null,
        ]);

        TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $actor->id,
            'stage' => TraceStage::STORAGE,
            'event_type' => strtoupper($log->action),
            'occurred_at' => $log->occurred_at,
            'quantity' => $lot->quantity,
            'unit' => $lot->unit,
            'status' => TraceEventStatus::VALIDATED,
            'metadata' => [
                'cold_chain_log_id' => $log->id,
                'temperature_c' => $log->temperature_c,
                'location_id' => $log->location_id,
            ],
        ]);

        return $log->fresh(['lot', 'location']);
    }

    public function getLotMap(Lot $lot): array
    {
        $events = $lot->events()->whereNotNull('latitude')->whereNotNull('longitude')->get();

        return [
            'lot_number' => $lot->lot_number,
            'product' => $lot->product?->name,
            'points' => $events->map(function (TraceEvent $event) {
                return [
                    'event_type' => $event->event_type,
                    'stage' => $event->stage?->value,
                    'latitude' => (float) $event->latitude,
                    'longitude' => (float) $event->longitude,
                    'occurred_at' => $event->occurred_at?->toDateTimeString(),
                ];
            })->values()->all(),
            'routes' => $lot->shipments()->get()->map(function (Shipment $shipment) {
                return [
                    'reference' => $shipment->reference,
                    'origin' => $shipment->origin?->name,
                    'destination' => $shipment->destination?->name,
                    'distance_km' => (float) $shipment->distance_km,
                    'transport_mode' => $shipment->transport_mode,
                    'status' => $shipment->status,
                ];
            })->values()->all(),
        ];
    }

    public function blockLot(Lot $lot, User $actor): Lot
    {
        $lot->update(['status' => LotStatus::BLOCKED]);

        TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $actor->id,
            'stage' => TraceStage::STORAGE,
            'event_type' => 'BLOCKED',
            'occurred_at' => now(),
            'status' => TraceEventStatus::VALIDATED,
            'metadata' => ['reason' => 'Lot bloqué par une décision de sécurité.'],
        ]);

        return $lot->fresh();
    }

    public function recallLot(Lot $lot, User $actor): Lot
    {
        $lot->update(['status' => LotStatus::RECALLED]);

        TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $actor->id,
            'stage' => TraceStage::DISTRIBUTION,
            'event_type' => 'RECALL',
            'occurred_at' => now(),
            'status' => TraceEventStatus::VALIDATED,
            'metadata' => ['reason' => 'Rappel produit déclenché.'],
        ]);

        return $lot->fresh();
    }
}
