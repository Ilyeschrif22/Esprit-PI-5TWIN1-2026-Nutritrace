<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sélection de rôle | NutriTrace</title>
</head>

<body>

    <main class="nutritrace-register-page">

        @include('auth.partials.nutritrace-brand')

        <!-- Partie droite -->
        <section class="nutritrace-register-form-section">

            <div class="nutritrace-register-form-container">

                <div class="nutritrace-register-form-header">
                    <h2>Choisissez votre rôle</h2>

                    <p class="nutritrace-register-form-header-description">
                        Sélectionnez le rôle qui correspond à votre activité
                        dans la chaîne de valeur.
                    </p>
                </div>

                <form method="POST" action="{{ route('role-selection.store') }}"
                    enctype="multipart/form-data" class="nutritrace-register-form">
                    @csrf

                    <!-- Rôle -->
                    <div class="nutritrace-register-field">
                        <label for="role">Rôle</label>

                        <select id="role" name="role" required onchange="toggleDocumentUpload()"
                            class="nutritrace-role-select">
                            <option value="">Choisir…</option>
                            <option value="producteur" @selected(old('role') === 'producteur')>Producteur</option>
                            <option value="transformateur" @selected(old('role') === 'transformateur')>Transformateur</option>
                            <option value="distributeur" @selected(old('role') === 'distributeur')>Distributeur</option>
                            <option value="consommateur" @selected(old('role') === 'consommateur')>Consommateur</option>
                        </select>

                        @error('role')
                            <span class="nutritrace-register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Document justificatif (requis pour les rôles professionnels) -->
                    <div id="document-upload" class="nutritrace-role-document" style="display: none;">
                        <label for="document" id="document-label">Document justificatif</label>

                        <input type="file" id="document" name="document" />

                        <small>Registre agricole, fiche technique, licence d’exploitation…</small>

                        @error('document')
                            <span class="nutritrace-register-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="nutritrace-register-submit">
                        Continuer
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="nutritrace-role-logout">
                    @csrf

                    <button type="submit">Se déconnecter</button>
                </form>

            </div>
        </section>

    </main>

    <script>
        function toggleDocumentUpload() {
            const role = document.getElementById('role').value;
            const documentUpload = document.getElementById('document-upload');
            const documentLabel = document.getElementById('document-label');
            const fileInput = document.getElementById('document');

            const rolesRequiringDocument = ['producteur', 'transformateur', 'distributeur'];

            if (rolesRequiringDocument.includes(role)) {
                documentUpload.style.display = 'block';

                const roleLabels = {
                    'producteur': 'Document prouvant que vous êtes un Producteur',
                    'transformateur': 'Document prouvant que vous êtes un Transformateur',
                    'distributeur': 'Document prouvant que vous êtes un Distributeur'
                };

                documentLabel.textContent = roleLabels[role];
                fileInput.required = true;
            } else {
                documentUpload.style.display = 'none';
                fileInput.required = false;
            }
        }
    </script>

    <style>
        .nutritrace-register-submit {
            width: 100%;
            margin: 18px 0 0;
        }

        .nutritrace-role-select {
            width: 100%;
            height: 44px;
            padding: 0 16px;

            border: 1px solid var(--border-color);
            outline: none;

            background: #ffffff;
            color: var(--text-color);
            font-family: inherit;
            font-size: 14px;

            transition: 0.2s ease;
        }

        .nutritrace-role-select:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(61, 155, 69, 0.1);
        }

        .nutritrace-role-document {
            display: flex;
            flex-direction: column;
            gap: 8px;

            margin-top: 4px;
            padding: 18px 20px;

            border: 1px dashed var(--border-color);
            border-radius: 12px;

            background: #ffffff;
        }

        .nutritrace-role-document label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-color);
        }

        .nutritrace-role-document input[type="file"] {
            font-family: inherit;
            font-size: 13px;
            color: var(--text-color);
        }

        .nutritrace-role-document small {
            color: var(--muted-text);
            font-size: 12px;
        }

        .nutritrace-role-logout {
            display: flex;
            justify-content: center;

            margin-top: 18px;
        }

        .nutritrace-role-logout button {
            border: none;
            background: none;

            color: var(--muted-text);
            font-family: inherit;
            font-size: 13px;
            text-decoration: underline;

            cursor: pointer;
        }
    </style>

</body>

</html>
