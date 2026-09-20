<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mot de passe oublié - NutriTrace</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nutritrace-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <main class="nutritrace-login-page">
        @include('auth.partials.nutritrace-brand')
        <section class="nutritrace-login-form-section">
            <div class="nutritrace-login-form-container">
                <div class="nutritrace-login-form-header">
                    <h2>Mot de passe oublié ?</h2>
                    <p class="nutritrace-login-form-header-description">Saisissez votre adresse e-mail et nous vous enverrons un lien pour choisir un nouveau mot de passe.</p>
                </div>
                @if (session('status'))
                    <p class="nutritrace-login-status">{{ session('status') }}</p>
                @endif
                <form class="nutritrace-login-form" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="nutritrace-login-field">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required autofocus autocomplete="email" />
                        @error('email') <span class="nutritrace-login-error">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="nutritrace-login-submit">Envoyer le lien</button>
                </form>
                <div class="nutritrace-login-register"><a href="{{ route('login') }}">Retour à la connexion</a></div>
            </div>
        </section>
    </main>
</body>
</html>