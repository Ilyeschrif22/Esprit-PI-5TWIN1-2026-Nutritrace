@extends('layouts.app')

@section('title', 'Sélection de rôle — '.config('app.name'))

@section('content')
    <div class="panel">
        <h1>Choisissez votre rôle</h1>
        <p class="subtitle">Sélectionnez le rôle qui correspond à votre activité dans la chaîne de valeur.</p>

        <form method="POST" action="{{ route('role-selection.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="field full">
                <label for="role">Rôle</label>
                <select id="role" name="role" required onchange="toggleDocumentUpload()">
                    <option value="">Choisir…</option>
                    <option value="producteur" @selected(old('role') === 'producteur')>Producteur</option>
                    <option value="transformateur" @selected(old('role') === 'transformateur')>Transformateur</option>
                    <option value="distributeur" @selected(old('role') === 'distributeur')>Distributeur</option>
                    <option value="consommateur" @selected(old('role') === 'consommateur')>Consommateur</option>
                </select>
                @error('role') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field full" id="document-upload" style="display: none;">
                <label for="document">Document justificatif</label>
                <input id="document" type="file" name="document">
                @error('document') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn">Continuer</button>
            </div>
        </form>
    </div>

    <script>
        function toggleDocumentUpload() {
            const role = document.getElementById('role').value;
            const documentUpload = document.getElementById('document-upload');
            const documentLabel = documentUpload.querySelector('label');
            
            const rolesRequiringDocument = ['producteur', 'transformateur', 'distributeur'];
            
            if (rolesRequiringDocument.includes(role)) {
                documentUpload.style.display = 'block';
                const roleLabels = {
                    'producteur': 'Document prouvant que vous êtes un Producteur',
                    'transformateur': 'Document prouvant que vous êtes un Transformateur',
                    'distributeur': 'Document prouvant que vous êtes un Distributeur'
                };
                documentLabel.textContent = roleLabels[role];
                document.getElementById('document').required = true;
            } else {
                documentUpload.style.display = 'none';
                document.getElementById('document').required = false;
            }
        }
    </script>
@endsection
