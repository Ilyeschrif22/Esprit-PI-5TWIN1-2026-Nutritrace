<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de bord | NutriTrace</title>
</head>

<body>

    <main class="nutritrace-register-page">

        @include('auth.partials.nutritrace-brand')

        <!-- Partie droite -->
        <section class="nutritrace-register-form-section">

            <div class="nutritrace-register-form-container">

                @php $user = auth()->user(); @endphp

                <div class="nutritrace-register-form-header">
                    <h2>Bonjour, {{ $user->fullname }}</h2>

                    <p class="nutritrace-register-form-header-description">
                        {{ $user->governorate }}
                        @if ($user->cin)
                            · CIN {{ $user->cin }}
                        @endif
                    </p>
                </div>

                <div class="nutritrace-dashboard-card">

                    <div class="nutritrace-dashboard-row">
                        <span class="nutritrace-dashboard-label">Email</span>
                        <span class="nutritrace-dashboard-value">{{ $user->email }}</span>
                    </div>

                    @if ($user->phone)
                        <div class="nutritrace-dashboard-row">
                            <span class="nutritrace-dashboard-label">Téléphone</span>
                            <span class="nutritrace-dashboard-value">{{ $user->phone }}</span>
                        </div>
                    @endif

                    @if ($user->birthdate)
                        <div class="nutritrace-dashboard-row">
                            <span class="nutritrace-dashboard-label">Date de naissance</span>
                            <span class="nutritrace-dashboard-value">{{ $user->birthdate->format('d/m/Y') }}</span>
                        </div>
                    @endif

                    @if ($user->city)
                        <div class="nutritrace-dashboard-row">
                            <span class="nutritrace-dashboard-label">Ville</span>
                            <span class="nutritrace-dashboard-value">{{ $user->city }}</span>
                        </div>
                    @endif

                    @if ($user->address)
                        <div class="nutritrace-dashboard-row">
                            <span class="nutritrace-dashboard-label">Adresse</span>
                            <span class="nutritrace-dashboard-value">{{ $user->address }}</span>
                        </div>
                    @endif

                </div>

                @if ($user->hasAnyRole(['producteur', 'transformateur', 'distributeur', 'consommateur']))
                    <div class="nutritrace-dashboard-role">
                        Rôle actuel :
                        <strong>{{ $user->getRoleNames()->first() }}</strong>
                    </div>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="nutritrace-register-submit">
                        Déconnexion
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

        .nutritrace-dashboard-card {
            display: grid;
            gap: 2px;

            margin-top: 22px;
            padding: 6px 22px;

            border: 1px solid var(--border-color);
            border-radius: 10px;

            background: #ffffff;
        }

        .nutritrace-dashboard-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            padding: 11px 0;

            border-bottom: 1px solid var(--border-color);

            font-size: 14px;
        }

        .nutritrace-dashboard-row:last-child {
            border-bottom: none;
        }

        .nutritrace-dashboard-label {
            color: var(--muted-text);
        }

        .nutritrace-dashboard-value {
            color: var(--text-color);
            font-weight: 600;
        }

        .nutritrace-dashboard-role {
            margin-top: 14px;

            color: var(--muted-text);
            font-size: 13px;
        }

        .nutritrace-dashboard-role strong {
            color: var(--green);
        }
    </style>

</body>

</html>