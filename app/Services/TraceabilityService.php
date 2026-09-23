<?php

namespace App\Services;

use App\Enums\LotStatus;
use App\Enums\TraceEventStatus;
use App\Enums\TraceStage;
use App\Events\TraceEventCreated;
use App\Models\Lot;
use App\Models\Product;
use App\Models\TraceAlert;
use App\Models\TraceEvent;
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
