<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bienvenue | NutriTrace</title>
</head>

<body>

    <main class="nutritrace-register-page">

        @include('auth.partials.nutritrace-brand')

        <!-- Partie droite -->
        <section class="nutritrace-register-form-section">

            <div class="nutritrace-register-form-container">

                <div class="nutritrace-register-form-header">
                    <h2>Bienvenue sur NutriTrace</h2>

                    <p class="nutritrace-register-form-header-description">
                        Suivez l’origine de vos aliments, découvrez leur parcours
                        et faites des choix plus éclairés.
                    </p>
                </div>

                <p class="nutritrace-welcome-text">
                    Rejoignez une communauté engagée pour une alimentation
                    plus sûre, plus transparente et plus durable.
                </p>

                <div class="nutritrace-welcome-actions">
                    @auth
                        <a class="nutritrace-register-submit" href="{{ route('dashboard') }}">
                            Accéder à mon tableau de bord
                        </a>
                    @else
                        <a class="nutritrace-register-submit" href="{{ route('register') }}">
                            Créer un compte
                        </a>

                        <a class="nutritrace-register-submit" href="{{ route('login') }}">
                            Se connecter
                        </a>
                    @endauth
                </div>

            </div>
        </section>

    </main>

    <style>
        .nutritrace-welcome-text {
            max-width: 460px;
            margin: 4px 0 0;
            color: var(--muted-text);
            font-size: 15px;
            line-height: 1.8;
        }

        .nutritrace-welcome-actions {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 34px;
        }

        .nutritrace-welcome-actions .nutritrace-register-submit {
            display: inline-flex;
            width: 100%;
            margin: 0;
            text-decoration: none;
        }
    </style>

</body>

</html>
