<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traceability</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
<div class="max-w-7xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-6">TRACEABILITY</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-sm text-slate-500">Total lots</p>
            <p class="text-2xl font-bold">{{ $lots->total() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-sm text-slate-500">Lots actifs</p>
            <p class="text-2xl font-bold">{{ $lots->where('status', 'active')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-sm text-slate-500">Alertes</p>
            <p class="text-2xl font-bold">{{ \App\Models\TraceAlert::count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left">Lot</th>
                    <th class="px-4 py-3 text-left">Produit</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Quantité</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lots as $lot)
                    <tr>
                        <td class="px-4 py-3"><a href="{{ route('traceability.show', $lot) }}" class="text-emerald-600 font-medium">{{ $lot->lot_number }}</a></td>
                        <td class="px-4 py-3">{{ $lot->product?->name ?? 'Produit' }}</td>
                        <td class="px-4 py-3"><span class="inline-flex px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs">{{ $lot->status }}</span></td>
                        <td class="px-4 py-3">{{ $lot->quantity }} {{ $lot->unit }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-slate-500">Aucun lot trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $lots->links() }}
    </div>
</div>
</body>
</html>
