<?php

namespace App\Http\Controllers;

use App\Actions\CreateProductionTrace;
use App\Enums\LotStatus;
use App\Enums\TraceStage;
use App\Http\Requests\Traceability\StoreProductionTraceRequest;
use App\Models\Lot;
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

        return view('pages.traceability', [
            'user' => Auth::user(),
            'lots' => $lots,
            'stats' => $stats,
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

        return view('pages.traceability', [
            'user' => Auth::user(),
            'stats' => $stats,
            'lots' => $lots,
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
            ->with('product')
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

                $origin = strtolower((string) ($lot->origin ?: $lot->location ?: 'Tunisie'));
                $coordinates = $this->coordinatesForLocation($origin);

                return [
                    'id' => $lot->id,
                    'lot_number' => $lot->lot_number,
                    'product_name' => $lot->product?->name ?? 'Produit',
                    'origin' => $lot->origin ?: $lot->location ?: 'Tunisie',
                    'stage' => $stage,
                    'status' => $lot->status?->value,
                    'lat' => $coordinates[0],
                    'lng' => $coordinates[1],
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
