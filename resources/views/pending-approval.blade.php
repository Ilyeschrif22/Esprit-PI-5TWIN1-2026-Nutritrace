<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>En attente d'approbation | NutriTrace</title>
</head>

<body>

    <main class="nutritrace-register-page">

        @include('auth.partials.nutritrace-brand')

        <!-- Partie droite -->
        <section class="nutritrace-register-form-section">

            <div class="nutritrace-register-form-container">

                <div class="nutritrace-register-form-header">
                    <h2>En attente d'approbation</h2>

                    <p class="nutritrace-register-form-header-description">
                        Votre demande de rôle est en cours de vérification.
                    </p>
                </div>

                <div class="nutritrace-pending-card">

                    @if ($roleDocument)
                        <div class="nutritrace-pending-row">
                            <span class="nutritrace-pending-label">Rôle demandé</span>
                            <strong class="nutritrace-pending-value">{{ ucfirst($roleDocument->role) }}</strong>
                        </div>

                        <div class="nutritrace-pending-row">
                            <span class="nutritrace-pending-label">Statut</span>
                            <strong class="nutritrace-pending-value">
                                @if ($roleDocument->status === 'pending')
                                    En attente
                                @elseif ($roleDocument->status === 'approved')
                                    Approuvé
                                @elseif ($roleDocument->status === 'rejected')
                                    Rejeté
                                @endif
                            </strong>
                        </div>

                        @if ($roleDocument->status === 'rejected' && $roleDocument->rejection_reason)
                            <div class="nutritrace-pending-row">
                                <span class="nutritrace-pending-label">Raison du rejet</span>
                                <strong class="nutritrace-pending-value">{{ $roleDocument->rejection_reason }}</strong>
                            </div>
                        @endif

                        @if ($roleDocument->status === 'rejected')
                            <a href="{{ route('role-selection.create') }}" class="nutritrace-pending-link">
                                Soumettre une nouvelle demande
                            </a>
                        @endif
                    @else
                        <p class="nutritrace-pending-empty">Aucune demande de rôle trouvée.</p>

                        <a href="{{ route('role-selection.create') }}" class="nutritrace-pending-link">
                            Sélectionner un rôle
                        </a>
                    @endif

                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="nutritrace-register-submit">
                        Se déconnecter
                    </button>
                </form>

            </div>
        </section>

    </main>

    <style>
        .nutritrace-register-submit {
            width: 100%;
            margin: 22px 0 0;
        }

        .nutritrace-pending-card {
            display: grid;
            gap: 2px;

            margin-top: 22px;
            padding: 6px 22px;

            border: 1px solid var(--border-color);
            border-radius: 10px;

            background: #ffffff;
        }

        .nutritrace-pending-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            padding: 11px 0;

            border-bottom: 1px solid var(--border-color);

            font-size: 14px;
        }

        .nutritrace-pending-row:last-child {
            border-bottom: none;
        }

        .nutritrace-pending-label {
            color: var(--muted-text);
        }

        .nutritrace-pending-value {
            color: var(--text-color);
            font-weight: 600;
        }

        .nutritrace-pending-empty {
            margin: 0;
            padding: 11px 0;

            color: var(--muted-text);
            font-size: 14px;
        }

        .nutritrace-pending-link {
            display: inline-block;

            padding: 11px 0;

            color: var(--green);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .nutritrace-pending-link:hover {
            text-decoration: underline;
        }
    </style>

</body>

</html>
