<?php

namespace App\Http\Controllers;

use App\Actions\CreateProductionTrace;
use App\Enums\LotStatus;
use App\Enums\TraceEventStatus;
use App\Enums\TraceStage;
use App\Http\Requests\Traceability\StoreProductionTraceRequest;
use App\Models\Lot;
use App\Models\TraceEvent;
use App\Services\TraceabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraceabilityController extends Controller
{
    public function __construct(protected TraceabilityService $traceabilityService)
    {
    }

    public function __invoke(): \Illuminate\View\View
    {
        return $this->index();
        
    }

    public function index()
    {
        $lots = Lot::query()
            ->with('product')
            ->latest()
            ->limit(12)
            ->get();

        $stats = $this->getDashboardStats();
        $products = \App\Models\Product::query()->orderBy('name')->get();
        $productionLots = \App\Models\Lot::query()->with('product')->orderByDesc('produced_at')->get();

        return view('pages.traceability', [
            'user' => Auth::user(),
            'lots' => $lots,
            'stats' => $stats,
            'products' => $products,
            'productionLots' => $productionLots,
        ]);
    }

    public function show(Lot $lot)
    {
        $lot->load(['product', 'events.actor']);

        return view('traceability.show', compact('lot'));
    }

    public function timeline(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $this->getTimelineData($lot),
        ]);
    }

    public function upstream(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'lot' => $lot->lot_number,
                'product' => $lot->product?->name,
                'events' => $this->getTimelineData($lot),
            ],
        ]);
    }

    public function downstream(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'lot' => $lot->lot_number,
                'status' => $lot->status?->value,
                'events' => $this->getTimelineData($lot),
            ],
        ]);
    }

    public function alerts(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $lot->alerts()->get(),
        ]);
    }

    public function dashboard()
    {
        $stats = $this->getDashboardStats();
        $lots = $this->getDashboardLotMap();
        $products = \App\Models\Product::query()->orderBy('name')->get();
        $productionLots = \App\Models\Lot::query()->with('product')->orderByDesc('produced_at')->get();

        return view('pages.traceability', [
            'user' => Auth::user(),
            'stats' => $stats,
            'lots' => $lots,
            'products' => $products,
            'productionLots' => $productionLots,
        ]);
    }

    public function storeLocationEvent(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'in:production,distribution'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'lot_id' => ['nullable', 'integer', 'exists:lots,id'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'lot_number' => ['nullable', 'string', 'max:255'],
            'quantity' => ['nullable', 'numeric', 'min:0.01'],
            'unit' => ['nullable', 'string', 'max:30'],
            'origin' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'production_type' => ['nullable', 'string', 'in:agricultural,livestock,aquaculture,horticultural,organic,conventional,mixed,other'],
            'production_method' => ['nullable', 'string', 'in:conventional,organic,integrated,sustainable,controlled_environment,local_traditional,other'],
            'production_reference' => ['nullable', 'string', 'max:255'],
            'produced_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $actor = Auth::user();
        $latitude = (float) $validated['latitude'];
        $longitude = (float) $validated['longitude'];

        if ($validated['action'] === 'production') {
            $product = null;
            if (! empty($validated['product_id'])) {
                $product = \App\Models\Product::query()->findOrFail($validated['product_id']);
            }

            if ($product && ! empty($validated['lot_id'])) {
                $lot = \App\Models\Lot::query()->findOrFail($validated['lot_id']);
                if ((int) $lot->product_id !== (int) $product->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le lot sélectionné ne correspond pas au produit sélectionné.',
                    ], 422);
                }
            }

            $payload = [
                'product_id' => $validated['product_id'] ?? null,
                'product_name' => $validated['product_name'] ?? ($product?->name ?? 'Produit local'),
                'lot_id' => $validated['lot_id'] ?? null,
                'lot_number' => $validated['lot_number'] ?? null,
                'quantity' => $validated['quantity'] ?? 1,
                'unit' => $validated['unit'] ?? 'kg',
                'category' => $validated['category'] ?? ($product?->category ?? 'Divers'),
                'origin' => $validated['origin'] ?? $validated['location'] ?? 'Localisation sélectionnée',
                'location' => $validated['location'] ?? $validated['origin'] ?? 'Localisation sélectionnée',
                'city' => $validated['location'] ?? $validated['origin'] ?? null,
                'produced_at' => $validated['produced_at'] ?? now()->toDateTimeString(),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'production_type' => $validated['production_type'] ?? 'agricultural',
                'production_method' => $validated['production_method'] ?? 'organic',
                'production_reference' => $validated['production_reference'] ?? null,
                'metadata' => [
                    'selected_coordinates' => [$latitude, $longitude],
                    'production_type' => $validated['production_type'] ?? 'agricultural',
                    'production_method' => $validated['production_method'] ?? 'organic',
                    'notes' => $validated['notes'] ?? null,
                ],
            ];

            $lot = $this->traceabilityService->recordProduction($payload, $actor);

            $event = $lot->events()->latest('occurred_at')->first();
            if ($event) {
                $event->update([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'metadata' => array_merge((array) ($event->metadata ?? []), [
                        'selected_coordinates' => [$latitude, $longitude],
                    ]),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Production enregistrée sur le point sélectionné.',
                'lot' => $lot->fresh(),
            ]);
        }

        $lot = $request->filled('lot_id')
            ? Lot::findOrFail($validated['lot_id'])
            : Lot::query()->latest()->firstOrFail();

        $destination = $validated['destination'] ?? $validated['location'] ?? $lot->location ?? 'Localisation sélectionnée';

        $lot->update([
            'location' => $destination,
            'metadata' => array_merge((array) ($lot->metadata ?? []), [
                'distribution' => [
                    'destination' => $destination,
                    'coordinates' => [$latitude, $longitude],
                ],
            ]),
        ]);

        $event = TraceEvent::create([
            'lot_id' => $lot->id,
            'actor_id' => $actor->id,
            'stage' => TraceStage::DISTRIBUTION,
            'event_type' => 'DISTRIBUTION',
            'occurred_at' => now(),
            'quantity' => $lot->quantity,
            'unit' => $lot->unit,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status' => TraceEventStatus::VALIDATED,
            'metadata' => [
                'destination' => $destination,
                'coordinates' => [$latitude, $longitude],
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Distribution ajoutée sur le point sélectionné.',
            'lot' => $lot->fresh(),
            'event' => $event,
        ]);
    }

    public function storeProduction(StoreProductionTraceRequest $request, CreateProductionTrace $action)
    {
        $lot = $action->handle($request->validated(), Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Production enregistrée.',
            'lot' => $lot,
        ]);
    }

    public function storeTransit(Lot $lot, Request $request)
    {
        $request->validate([
            'transport_mode' => ['nullable', 'string', 'max:50'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'origin' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'departed_at' => ['nullable', 'date'],
        ]);

        $updatedLot = $this->traceabilityService->recordTransit($lot, $request->all(), Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Lot mis en transit.',
            'lot' => $updatedLot,
        ]);
    }

    protected function getDashboardStats(): array
    {
        return [
            'total_lots' => Lot::count(),
            'active_lots' => Lot::where('status', LotStatus::ACTIVE->value)->count(),
            'in_transit_lots' => Lot::where('status', LotStatus::IN_TRANSIT->value)->count(),
            'blocked_lots' => Lot::where('status', LotStatus::BLOCKED->value)->count(),
            'active_alerts' => \App\Models\TraceAlert::whereNull('resolved_at')->count(),
            'environment_score' => 88,
            'transport_coverage' => '94%',
            'co2_coverage' => '91%',
            'complete_traceability' => '87%',
            'traceability_alerts' => 2,
            'compliant_lots' => '—',
            'verified_certifications' => '—',
            'total_distance' => '—',
            'co2_emissions' => '—',
        ];
    }

    protected function getDashboardLotMap()
    {
        return Lot::query()
            ->with(['product', 'shipments.origin', 'shipments.destination'])
            ->latest()
            ->limit(12)
            ->get()
            ->map(function (Lot $lot) {
                $lastEvent = $lot->events()->latest('occurred_at')->first();
                $stage = $lastEvent?->stage?->value ?? match ($lot->status) {
                    LotStatus::IN_TRANSIT => TraceStage::TRANSPORT->value,
                    LotStatus::ACTIVE => TraceStage::PRODUCTION->value,
                    default => 'default',
                };

                $productionLocationId = data_get($lot->metadata, 'production_location_id');
                $productionLocation = $productionLocationId ? \App\Models\Location::find($productionLocationId) : null;

                $originName = $productionLocation?->name ?? $lot->origin ?: $lot->location ?: 'Tunisie';
                $originCoordinates = $productionLocation && $productionLocation->latitude !== null && $productionLocation->longitude !== null
                    ? [$productionLocation->latitude, $productionLocation->longitude]
                    : $this->coordinatesForLocation((string) $originName);

                $shipment = $lot->shipments()->latest('departed_at')->first();
                $destinationName = $shipment?->destination?->name ?? data_get($lot->metadata, 'transit.destination');
                $destinationCoordinates = $shipment && $shipment->destination && $shipment->destination->latitude !== null && $shipment->destination->longitude !== null
                    ? [$shipment->destination->latitude, $shipment->destination->longitude]
                    : data_get($lot->metadata, 'transit.coordinates');

                return [
                    'id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'product_name' => $lot->product?->name ?? 'Produit',
                    'origin' => $originName,
                    'producer' => $productionLocation?->producer?->fullname ?? $lot->origin ?? 'Producteur',
                    'stage' => $stage,
                    'status' => $lot->status?->value,
                    'lat' => $originCoordinates[0],
                    'lng' => $originCoordinates[1],
                    'production_location' => [
                        'name' => $originName,
                        'lat' => $originCoordinates[0],
                        'lng' => $originCoordinates[1],
                    ],
                    'destination' => $destinationName,
                    'destination_coordinates' => $destinationCoordinates,
                    'route' => $destinationCoordinates && $originCoordinates ? [
                        'origin' => $originCoordinates,
                        'destination' => $destinationCoordinates,
                    ] : null,
                ];
            });
    }

    protected function getTimelineData(Lot $lot): array
    {
        return $lot->events()->with('actor')->get()->map(function ($event) {
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

    protected function coordinatesForLocation(string $location): array
    {
        $normalized = strtolower(trim($location ?: 'tunisie'));

        $productionPoints = [
            'nabeul' => [36.4511, 10.7322],
            'sfax' => [34.7406, 10.7604],
            'tunis' => [36.8065, 10.1815],
            'sousse' => [35.8254, 10.6367],
            'kairouan' => [35.6781, 10.0969],
            'gabes' => [33.8815, 10.0978],
            'tunisie' => [36.8065, 10.1815],
            'default' => [36.8065, 10.1815],
        ];

        foreach ($productionPoints as $city => $coordinates) {
            if ($normalized === $city || str_contains($normalized, $city)) {
                return $coordinates;
            }
        }

        return $productionPoints['default'];
    }
}
