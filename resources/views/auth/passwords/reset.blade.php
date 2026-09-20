<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nouveau mot de passe - NutriTrace</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nutritrace-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <main class="nutritrace-login-page">
        @include('auth.partials.nutritrace-brand')
        <section class="nutritrace-login-form-section">
            <div class="nutritrace-login-form-container">
                <div class="nutritrace-login-form-header">
                    <h2>Nouveau mot de passe</h2>
                    <p class="nutritrace-login-form-header-description">Choisissez un nouveau mot de passe sécurisé pour votre compte.</p>
                </div>
                <form class="nutritrace-login-form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="nutritrace-login-field">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email" />
                        @error('email') <span class="nutritrace-login-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="nutritrace-login-field">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password" />
                        @error('password') <span class="nutritrace-login-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="nutritrace-login-field">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    <button type="submit" class="nutritrace-login-submit">Réinitialiser le mot de passe</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>