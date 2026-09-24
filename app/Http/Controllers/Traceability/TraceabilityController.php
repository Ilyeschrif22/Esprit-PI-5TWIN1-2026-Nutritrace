<?php

namespace App\Http\Controllers\Traceability;

use App\Actions\CreateProductionTrace;
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

    public function publicTrace(string $token)
    {
        $data = $this->traceabilityService->getPublicTrace($token);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function map(Lot $lot)
    {
        return response()->json([
            'success' => true,
            'data' => $this->traceabilityService->getLotMap($lot),
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

    public function storeTransformation(Request $request)
    {
        $validated = $request->validate([
            'input_lot_id' => 'required|exists:lots,id',
            'process_name' => 'sometimes|string|max:255',
            'output_quantity' => 'required|numeric|min:0',
            'loss_quantity' => 'nullable|numeric|min:0',
            'location_id' => 'nullable|exists:locations,id',
            'occurred_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $transformation = $this->traceabilityService->createTransformation($validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Transformation enregistrée.',
            'transformation' => $transformation,
        ]);
    }

    public function storeColdChain(Request $request)
    {
        $validated = $request->validate([
            'lot_id' => 'required|exists:lots,id',
            'location_id' => 'nullable|exists:locations,id',
            'action' => 'required|string|max:255',
            'temperature_c' => 'nullable|numeric',
            'occurred_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $log = $this->traceabilityService->createColdChainLog($validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Événement de chaîne du froid enregistré.',
            'event' => $log,
        ]);
    }

    public function storeShipment(Request $request)
    {
        $validated = $request->validate([
            'lot_id' => 'required|exists:lots,id',
            'reference' => 'sometimes|string|max:255',
            'origin_location_id' => 'required|exists:locations,id',
            'destination_location_id' => 'required|exists:locations,id',
            'transport_mode' => 'required|string',
            'carrier' => 'nullable|string|max:255',
            'vehicle_reference' => 'nullable|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'departed_at' => 'nullable|date',
            'expected_arrival_at' => 'nullable|date|after_or_equal:departed_at',
        ]);

        $shipment = $this->traceabilityService->createShipment($validated, Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Expédition enregistrée.',
            'shipment' => $shipment->load(['lot', 'origin', 'destination']),
        ]);
    }

    public function dashboard()
    {
        $stats = [
            'total_lots' => Lot::count(),
            'active_lots' => Lot::where('status', 'active')->count(),
            'blocked_lots' => Lot::where('status', 'blocked')->count(),
        ];

        $lots = Lot::with('product')->latest()->limit(12)->get()->map(function (Lot $lot) {
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
                'lot_number' => $lot->lot_number,
                'product_name' => $lot->product?->name ?? 'Produit',
                'origin' => $lot->origin ?: $lot->location ?: 'Tunisie',
                'lat' => $coords[0],
                'lng' => $coords[1],
            ];
        });

        return view('traceability.dashboard', compact('stats', 'lots'));
    }
}
