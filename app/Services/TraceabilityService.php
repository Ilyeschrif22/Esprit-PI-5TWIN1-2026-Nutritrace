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

            $origin = $data['origin'] ?? $data['location'] ?? 'N/A';
            $location = $data['location'] ?? $origin;
            $coordinates = $this->resolveCoordinates($origin ?? $location);

            $lot = Lot::create([
                'product_id' => $product->id,
                'lot_number' => $data['lot_number'] ?? 'LOT-'.strtoupper(Str::random(8)),
                'status' => LotStatus::ACTIVE,
                'quantity' => $quantity,
                'unit' => $data['unit'] ?? 'kg',
                'origin' => $origin,
                'location' => $location,
                'produced_at' => $data['produced_at'] ?? now(),
                'public_token' => $data['public_token'] ?? (string) Str::ulid(),
                'metadata' => array_merge([
                    'coordinates' => $coordinates,
                ], (array) ($data['metadata'] ?? [])),
            ]);

            $event = TraceEvent::create([
                'lot_id' => $lot->id,
                'actor_id' => $actor->id,
                'stage' => TraceStage::PRODUCTION,
                'event_type' => 'PRODUCTION',
                'occurred_at' => $lot->produced_at ?? now(),
                'quantity' => $quantity,
                'unit' => $lot->unit,
                'latitude' => $coordinates[0],
                'longitude' => $coordinates[1],
                'status' => TraceEventStatus::VALIDATED,
                'metadata' => [
                    'origin' => $lot->origin,
                    'location' => $lot->location,
                    'coordinates' => $coordinates,
                ],
            ]);

            event(new TraceEventCreated($event));

            return $lot->fresh();
        });
    }

    public function recordTransit(Lot $lot, array $data, User $actor): Lot
    {
        return DB::transaction(function () use ($lot, $data, $actor) {
            $destination = $data['destination'] ?? $data['location'] ?? $lot->location ?? 'N/A';
            $coordinates = $this->resolveCoordinates($destination);

            $lot->update([
                'status' => LotStatus::IN_TRANSIT,
                'location' => $destination,
                'metadata' => array_merge((array) ($lot->metadata ?? []), [
                    'transit' => [
                        'destination' => $destination,
                        'transport_mode' => $data['transport_mode'] ?? null,
                        'carrier' => $data['carrier'] ?? null,
                        'coordinates' => $coordinates,
                    ],
                ]),
            ]);

            $event = TraceEvent::create([
                'lot_id' => $lot->id,
                'actor_id' => $actor->id,
                'stage' => TraceStage::TRANSPORT,
                'event_type' => 'IN_TRANSIT',
                'occurred_at' => $data['departed_at'] ?? now(),
                'quantity' => $lot->quantity,
                'unit' => $lot->unit,
                'latitude' => $coordinates[0],
                'longitude' => $coordinates[1],
                'status' => TraceEventStatus::VALIDATED,
                'metadata' => [
                    'origin' => $lot->origin,
                    'destination' => $destination,
                    'transport_mode' => $data['transport_mode'] ?? null,
                    'carrier' => $data['carrier'] ?? null,
                    'coordinates' => $coordinates,
                ],
            ]);

            event(new TraceEventCreated($event));

            return $lot->fresh();
        });
    }

    protected function resolveCoordinates(?string $location): array
    {
        $normalized = strtolower(trim((string) ($location ?? 'Tunisie')));

        $mapping = [
            'nabeul' => [36.4511, 10.7322],
            'sfax' => [34.7406, 10.7604],
            'tunis' => [36.8065, 10.1815],
            'sousse' => [35.8254, 10.6367],
            'kairouan' => [35.6781, 10.0969],
            'gabes' => [33.8815, 10.0978],
            'tunisville' => [36.8065, 10.1815],
            'ariana' => [36.8625, 10.1956],
            'beja' => [36.7256, 9.1817],
            'ben arous' => [36.7531, 10.2217],
            'bizerte' => [37.2744, 9.8739],
            'monastir' => [35.7777, 10.8262],
            'mahdia' => [35.5047, 11.0622],
            'tozeur' => [33.9197, 8.1335],
            'kasserine' => [35.1676, 8.8365],
            'gafsa' => [34.425, 8.7842],
            'médenine' => [33.354, 10.5055],
            'medenine' => [33.354, 10.5055],
            'tataouine' => [33.9343, 10.4524],
            'siliana' => [36.0848, 9.3709],
            'zaghouan' => [36.4021, 10.1425],
            'kebili' => [33.7049, 8.9680],
            'le kef' => [36.1820, 8.7147],
            'sidi bouzid' => [35.0382, 9.4849],
            'jendouba' => [36.5013, 8.7803],
            'gabes' => [33.8815, 10.0978],
            'tunisie' => [36.8065, 10.1815],
            'n/a' => [36.8065, 10.1815],
            'na' => [36.8065, 10.1815],
            'default' => [36.8065, 10.1815],
        ];

        foreach ($mapping as $city => $coordinates) {
            if ($normalized === $city || str_contains($normalized, $city)) {
                return $coordinates;
            }
        }

        return [36.8065, 10.1815];
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
