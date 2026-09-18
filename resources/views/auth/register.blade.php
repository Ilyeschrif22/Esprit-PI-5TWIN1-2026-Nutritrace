<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Créer un compte | NutriTrace</title>

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">

 
</head>

<body>

    <main class="nutritrace-register-page">

        <!-- Partie gauche -->
        <section class="nutritrace-register-brand">

            <div class="nutritrace-register-brand-content">

                <div class="nutritrace-register-logo">
                    <img src="{{ asset('images/nutritrace-logo.png') }}" class="nutritrace-logo">

                    <span class="nutritrace-register-brand-text">
                        Nutri<span>Trace</span>
                    </span>
                </div>

                <p class="nutritrace-register-eyebrow">
                    TRAÇABILITÉ ALIMENTAIRE
                </p>

                <h2>
                    La transparence<br />
                    dans votre assiette.
                </h2>

                <p class="nutritrace-register-description">
                    Suivez l’origine de vos aliments, découvrez leur parcours
                    et faites des choix plus éclairés.
                </p>

                <div class="nutritrace-register-features">

                    <!-- Feature 1 -->
                    <div class="nutritrace-register-feature">

                        <div class="nutritrace-register-feature-icon">
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
                    <div class="nutritrace-register-feature">

                        <div class="nutritrace-register-feature-icon">
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
                    <div class="nutritrace-register-feature">

                        <div class="nutritrace-register-feature-icon">
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

            <div class="nutritrace-register-brand-decoration"></div>
        </section>


        <!-- Partie droite -->
        <section class="nutritrace-register-form-section">

            <div class="nutritrace-register-form-container">

                <div class="nutritrace-register-form-header">
                    <h2>Créer un compte</h2>

                    <p class="nutritrace-register-form-header-description">
                        Rejoignez NutriTrace et contribuez à une
                        alimentation plus sûre et transparente.
                    </p>
                </div>

                <form class="nutritrace-register-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Nom complet et CIN -->
                    <div class="nutritrace-register-fields-row">

                        <!-- Nom complet -->
                        <div class="nutritrace-register-field">
                            <label for="fullname">Nom complet</label>

                            <div class="nutritrace-register-input-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <circle cx="12" cy="8" r="5"></circle>
                                    <path d="M20 21a8 8 0 0 0-16 0"></path>
                                </svg>

                                <input
                                    type="text"
                                    id="fullname"
                                    name="fullname"
                                    value="{{ old('fullname') }}"
                                    placeholder="Entrez votre nom complet"
                                    required
                                    autofocus
                                />
                            </div>
                            @error('fullname') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- CIN -->
                        <div class="nutritrace-register-field">
                            <label for="cin">CIN</label>

                            <div class="nutritrace-register-input-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                    <circle cx="8" cy="12" r="2"></circle>
                                    <path d="M13 10h4"></path>
                                    <path d="M13 14h4"></path>
                                </svg>

                                <input
                                    type="text"
                                    id="cin"
                                    name="cin"
                                    value="{{ old('cin') }}"
                                    placeholder="Entrez votre CIN"
                                    maxlength="8"
                                    inputmode="numeric"
                                    required
                                />
                            </div>
                            @error('cin') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                    </div>


                    <!-- Email et téléphone -->
                    <div class="nutritrace-register-fields-row">

                        <!-- Email -->
                        <div class="nutritrace-register-field">
                            <label for="email">Adresse e-mail</label>

                            <div class="nutritrace-register-input-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                    <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                </svg>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="exemple@email.com"
                                    required
                                    autocomplete="username"
                                />
                            </div>
                            @error('email') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="nutritrace-register-field">
                            <label for="phone">Téléphone</label>

                            <div class="nutritrace-register-input-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                        19.79 19.79 0 0 1-8.63-3.07
                                        19.5 19.5 0 0 1-6-6
                                        19.79 19.79 0 0 1-3.07-8.67
                                        A2 2 0 0 1 4.11 2h3
                                        a2 2 0 0 1 2 1.72
                                        12.84 12.84 0 0 0 .7 2.81
                                        2 2 0 0 1-.45 2.11L8.09 9.91
                                        a16 16 0 0 0 6 6l1.27-1.27
                                        a2 2 0 0 1 2.11-.45
                                        12.84 12.84 0 0 0 2.81.7
                                        A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="Ex : 20 123 456"
                                    required
                                />
                            </div>
                            @error('phone') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                    </div>


                    <!-- Date de naissance et gouvernorat -->
                    <div class="nutritrace-register-fields-row">

                        <!-- Date de naissance -->
                        <div class="nutritrace-register-field">
                            <label for="birthdate">Date de naissance</label>

                            <div class="nutritrace-register-input-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                    <line x1="16" x2="16" y1="2" y2="6"></line>
                                    <line x1="8" x2="8" y1="2" y2="6"></line>
                                    <line x1="3" x2="21" y1="10" y2="10"></line>
                                </svg>

                                <input
                                    type="date"
                                    id="birthdate"
                                    name="birthdate"
                                    value="{{ old('birthdate') }}"
                                    required
                                />
                            </div>
                            @error('birthdate') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- Gouvernorat -->
                        <div class="nutritrace-register-field">
                            <label for="governorate">Gouvernorat</label>

                            <div class="nutritrace-register-input-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#0b4145" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>

                                <select id="governorate" name="governorate" required class="nutritrace-register-select">
                                    <option value="" disabled {{ old('governorate') ? '' : 'selected' }}>
                                        Sélectionnez votre gouvernorat
                                    </option>
                                    @foreach ([
                                        'Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'Nabeul', 'Zaghouan', 'Bizerte',
                                        'Béja', 'Jendouba', 'Le Kef', 'Siliana', 'Kairouan', 'Kasserine', 'Sidi Bouzid',
                                        'Sousse', 'Monastir', 'Mahdia', 'Sfax', 'Gabès', 'Médenine', 'Tataouine',
                                        'Gafsa', 'Tozeur', 'Kébili',
                                    ] as $gov)
                                        <option value="{{ $gov }}" @selected(old('governorate') === $gov)>{{ $gov }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('governorate') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <!-- Ville et Adresse -->
                    <div class="nutritrace-register-fields-row">

                        <!-- Ville -->
                        <div class="nutritrace-register-field">
                            <label for="city">Ville</label>

                            <div class="nutritrace-register-input-wrapper">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#0b4145"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <path d="M3 21h18"></path>
                                    <path d="M5 21V7l7-4 7 4v14"></path>
                                    <path d="M9 21v-6h6v6"></path>
                                    <path d="M9 9h.01"></path>
                                    <path d="M12 9h.01"></path>
                                    <path d="M15 9h.01"></path>
                                </svg>

                                <input
                                    type="text"
                                    id="city"
                                    name="city"
                                    value="{{ old('city') }}"
                                    placeholder="Entrez votre ville"
                                    required
                                />

                            </div>
                            @error('city') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>


                        <!-- Adresse -->
                        <div class="nutritrace-register-field">
                            <label for="address">Adresse</label>

                            <div class="nutritrace-register-input-wrapper">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#0b4145"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>

                                <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="Entrez votre adresse complète"
                                    required
                                />

                            </div>
                            @error('address') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                    </div>



                    <!-- Password fields row -->
                    <div class="nutritrace-register-fields-row">

                        <!-- Password field -->
                        <div class="nutritrace-register-field">
                            <label for="password">Mot de passe</label>

                            <div class="nutritrace-register-password-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#0b4145"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <circle cx="12" cy="16" r="1"></circle>
                                    <rect width="18" height="12" x="3" y="10" rx="2"></rect>
                                    <path d="M7 10V7a5 5 0 0 1 9.33-2.5"></path>
                                </svg>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />

                                <button
                                    type="button"
                                    class="nutritrace-register-password-toggle"
                                    onclick="togglePassword('password', this)">
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
                                </button>
                            </div>
                            @error('password') <span class="nutritrace-register-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confirm Password field -->
                        <div class="nutritrace-register-field">
                            <label for="password_confirmation">Confirmer le mot de passe</label>

                            <div class="nutritrace-register-password-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#0b4145"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="nutritrace-register-input-icon">
                                    <circle cx="12" cy="16" r="1"></circle>
                                    <rect width="18" height="12" x="3" y="10" rx="2"></rect>
                                    <path d="M7 10V7a5 5 0 0 1 9.33-2.5"></path>
                                </svg>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />

                                <button
                                    type="button"
                                    class="nutritrace-register-password-toggle"
                                    onclick="togglePassword('password_confirmation', this)">
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
                                </button>
                            </div>
                        </div>

                    </div>

                    <label class="nutritrace-register-terms">
                        <input type="checkbox" name="terms" required />

                        <span>
                            J’accepte les
                            <a href="#">conditions d’utilisation</a>
                            et la
                            <a href="#">politique de confidentialité</a>.
                        </span>
                    </label>

                    <!-- Submit button -->
                    <button type="submit" class="nutritrace-register-submit">
                        Créer mon compte
                    </button>

                    <!-- Login redirect -->
                    <div class="nutritrace-register-login">
                        <p>Vous avez déjà un compte ?</p>
                        <a href="{{ route('login') }}">Se connecter</a>
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

