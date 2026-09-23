<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lot {{ $lot->lot_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-2">Lot {{ $lot->lot_number }}</h1>
        <p class="text-slate-600 mb-4">Produit : {{ $lot->product?->name ?? 'N/A' }}</p>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><dt class="text-slate-500">Statut</dt><dd class="font-semibold">{{ $lot->status }}</dd></div>
            <div><dt class="text-slate-500">Quantité</dt><dd class="font-semibold">{{ $lot->quantity }} {{ $lot->unit }}</dd></div>
            <div><dt class="text-slate-500">Origine</dt><dd class="font-semibold">{{ $lot->origin }}</dd></div>
            <div><dt class="text-slate-500">Localisation</dt><dd class="font-semibold">{{ $lot->location }}</dd></div>
        </dl>
    </div>
</div>
</body>
</html>
