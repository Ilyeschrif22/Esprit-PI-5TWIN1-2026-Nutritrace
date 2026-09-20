<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Connexion NutriTrace</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nutritrace-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>

    <main class="nutritrace-login-page">

        <!-- Partie gauche -->
        <section class="nutritrace-login-brand">

            <div class="nutritrace-login-brand-content">

                <div class="nutritrace-login-logo">
                    <img src="{{ asset('images/nutritrace-logo.png') }}" class="nutritrace-logo">

                    <span class="nutritrace-login-brand-text">
                        Nutri<span>Trace</span>
                    </span>
                </div>

                <p class="nutritrace-login-eyebrow">
                    TRAÇABILITÉ ALIMENTAIRE
                </p>

                <h2>
                    La transparence<br />
                    dans votre assiette.
                </h2>

                <p class="nutritrace-login-description">
                    Suivez l’origine de vos aliments, découvrez leur parcours
                    et faites des choix plus éclairés.
                </p>

                <div class="nutritrace-login-features">

                    <!-- Feature 1 -->
                    <div class="nutritrace-login-feature">

                        <div class="nutritrace-login-feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3 5 6v5c0 4.5 3 8.5 7 10 4-1.5 7-5.5 7-10V6l-7-3Z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </div>

                        <div>
                            <h3>Traçabilité complète</h3>
                            <p>De la ferme à votre assiette.</p>
                        </div>

                    </div>

                    <!-- Feature 2 -->
                    <div class="nutritrace-login-feature">

                        <div class="nutritrace-login-feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21c0-7.5 3.5-13 9-16-1 7-4 12-9 16Z" />
                                <path d="M12 21C8 17 4.5 13 3 8c5 .5 8 4 9 13Z" />
                                <path d="M12 21V9" />
                            </svg>
                        </div>

                        <div>
                            <h3>Qualité et sécurité</h3>
                            <p>alimentaire</p>
                        </div>

                    </div>

                    <!-- Feature 3 -->
                    <div class="nutritrace-login-feature">

                        <div class="nutritrace-login-feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m3 8 9 5 9-5" />
                                <path d="M3 8v9l9 5 9-5V8" />
                                <path d="M12 13v9" />
                                <path d="M12 4v5" />
                                <path d="M9.5 6.5 12 4l2.5 2.5" />
                            </svg>
                        </div>

                        <div>
                            <h3>Un avenir plus durable</h3>
                            <p>Pour vous et la planète.</p>
                        </div>

                    </div>

                </div>
            </div>

            <div class="nutritrace-login-brand-decoration"></div>
        </section>


        <!-- Partie droite -->
        <section class="nutritrace-login-form-section">

            <div class="nutritrace-login-form-container">

                <div class="nutritrace-login-form-header">
                    <h2>Se connecter</h2>

                    <p class="nutritrace-login-form-header-description">
                        Ravis de vous revoir. Connectez-vous pour continuer
                        à suivre vos aliments.
                    </p>
                </div>

                @if (session('status'))
                    <p class="nutritrace-login-status">{{ session('status') }}</p>
                @endif

                <form class="nutritrace-login-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="nutritrace-login-field">
                        <label for="email">Adresse e-mail</label>

                        <div class="nutritrace-login-input-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="#0b4145" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="nutritrace-login-input-icon">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                            </svg>

                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="exemple@email.com" required autofocus autocomplete="username" />
                        </div>
                        @error('email') <span class="nutritrace-login-error">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div class="nutritrace-login-field">
                        <label for="password">Mot de passe</label>

                        <div class="nutritrace-login-password-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="#0b4145" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="nutritrace-login-input-icon">
                                <circle cx="12" cy="16" r="1"></circle>
                                <rect width="18" height="12" x="3" y="10" rx="2"></rect>
                                <path d="M7 10V7a5 5 0 0 1 9.33-2.5"></path>
                            </svg>

                            <input type="password" id="password" name="password" placeholder="••••••••" required
                                autocomplete="current-password" />

                            <button type="button" class="nutritrace-login-password-toggle"
                                onclick="togglePassword('password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-eye-off">
                                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c5 0 8.27 4.5 9.5 7a13.16 13.16 0 0 1-1.67 2.68">
                                    </path>
                                    <path
                                        d="M6.61 6.61A13.52 13.52 0 0 0 2.5 12c1.23 2.5 4.5 7 9.5 7a10.43 10.43 0 0 0 2.27-.25">
                                    </path>
                                    <line x1="2" y1="2" x2="22" y2="22"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="nutritrace-login-options">

                            <label class="nutritrace-login-remember">
                                <input type="checkbox" name="remember" />
                                <span>Se souvenir de moi</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="nutritrace-login-forgot">
                                    Mot de passe oublié ?
                                </a>
                            @endif

                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="nutritrace-login-submit">
                            Se connecter
                        </button>

                        <!-- Divider -->
                        <div class="nutritrace-login-divider">
                            <span>Ou</span>
                        </div>

                        <!-- Google sign-in -->
                        <a href="#" class="nutritrace-login-google">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                                <path fill="#FFC107"
                                    d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" />
                                <path fill="#FF3D00"
                                    d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z" />
                                <path fill="#4CAF50"
                                    d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" />
                                <path fill="#1976D2"
                                    d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" />
                            </svg>
                            <span>Se connecter avec Google</span>
                        </a>


                    <!-- Register redirect -->
                    <div class="nutritrace-login-register">
                        <p>Vous n’avez pas de compte ?</p>
                        <a href="{{ route('register') }}">Créer un compte</a>
                    </div>
                </form>

            </div>
        </section>

    </main>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);

            if (input.type === "password") {
                input.type = "text";

                button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="20"
             height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="#0b4145"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="lucide lucide-eye">
            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
    `;
            } else {
                input.type = "password";

                button.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="20"
             height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="#0b4145"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="lucide lucide-eye-off">
            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c5 0 8.27 4.5 9.5 7a13.16 13.16 0 0 1-1.67 2.68"></path>
            <path d="M6.61 6.61A13.52 13.52 0 0 0 2.5 12c1.23 2.5 4.5 7 9.5 7a10.43 10.43 0 0 0 2.27-.25"></path>
            <line x1="2" y1="2" x2="22" y2="22"></line>
        </svg>
    `;
            }
        }
    </script>

</body>

</html>