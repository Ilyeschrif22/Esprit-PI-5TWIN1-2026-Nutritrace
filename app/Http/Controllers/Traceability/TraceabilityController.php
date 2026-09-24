<?php

namespace App\Http\Controllers\Traceability;

use App\Actions\CreateProductionTrace;
use App\Enums\LotStatus;
use App\Enums\TraceStage;
use App\Http\Controllers\Controller;
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

    public function index()
    {
        $lots = Lot::with('product')->latest()->paginate(15);

        return view('traceability.index', compact('lots'));
    }

    public function show(Lot $lot)
    {
        return view('traceability.show', compact('lot'));
    }

    public function timeline(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $this->traceabilityService->getTimeline($lot),
        ]);
    }

    public function upstream(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $this->traceabilityService->getUpstream($lot),
        ]);
    }

    public function downstream(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $this->traceabilityService->getDownstream($lot),
        ]);
    }

    public function alerts(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $lot->alerts()->get(),
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

    public function dashboard()
    {
        $stats = [
            'total_lots' => Lot::count(),
            'active_lots' => Lot::where('status', LotStatus::ACTIVE->value)->count(),
            'in_transit_lots' => Lot::where('status', LotStatus::IN_TRANSIT->value)->count(),
            'blocked_lots' => Lot::where('status', LotStatus::BLOCKED->value)->count(),
        ];

        $lots = Lot::with('product')->latest()->limit(12)->get()->map(function (Lot $lot) {
            $lastEvent = $lot->events()->latest('occurred_at')->first();
            $stage = $lastEvent?->stage?->value ?? match ($lot->status) {
                LotStatus::IN_TRANSIT => TraceStage::TRANSPORT->value,
                LotStatus::ACTIVE => TraceStage::PRODUCTION->value,
                default => 'default',
            };

            $origin = strtolower((string) ($lot->origin ?: $lot->location ?: 'Tunisie'));
            $productionPoints = [
                'nabeul' => [36.4511, 10.7322],
                'sfax' => [34.7406, 10.7604],
                'tunis' => [36.8065, 10.1815],
                'sousse' => [35.8254, 10.6367],
                'kairouan' => [35.6781, 10.0969],
                'gabes' => [33.8815, 10.0978],
            ];

            $coords = $productionPoints[$origin] ?? [36.8065, 10.1815];

            return [
                'id' => $lot->id,
                'lot_number' => $lot->lot_number,
                'product_name' => $lot->product?->name ?? 'Produit',
                'origin' => $lot->origin ?: $lot->location ?: 'Tunisie',
                'stage' => $stage,
                'status' => $lot->status?->value,
                'lat' => $coords[0],
                'lng' => $coords[1],
            ];
        });

        return view('traceability.dashboard', compact('stats', 'lots'));
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
}
