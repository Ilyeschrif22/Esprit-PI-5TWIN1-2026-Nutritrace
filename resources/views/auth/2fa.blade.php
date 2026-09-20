<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Vérification en deux étapes - NutriTrace</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nutritrace-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/2fa.css') }}">

</head>

<body>

    <main class="nutritrace-login-page">

        <!-- Partie gauche (identique à la page de connexion) -->
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

                <div class="nutritrace-2fa-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="12" x="3" y="10" rx="2"></rect>
                        <circle cx="12" cy="16" r="1"></circle>
                        <path d="M7 10V7a5 5 0 0 1 9.33-2.5"></path>
                    </svg>
                </div>

                <div class="nutritrace-login-form-header">
                    <h2>Vérification en deux étapes</h2>

                    <p class="nutritrace-login-form-header-description">
                        Entrez le code à 6 chiffres envoyé à
                        <strong>{{ $maskedEmail ?? 'votre adresse e-mail' }}</strong>
                        pour confirmer votre identité.
                    </p>
                </div>

                <form class="nutritrace-login-form" method="POST" action="{{ route('2fa.verify') }}">
                    @csrf

                    <!-- Code OTP -->
                    <div class="nutritrace-2fa-field">
                        <label for="otp-1">Code de vérification</label>

                        <div class="nutritrace-2fa-otp-group" id="otp-group">
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                class="nutritrace-2fa-otp-input" id="otp-1" autocomplete="one-time-code" autofocus />
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                class="nutritrace-2fa-otp-input" id="otp-2" />
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                class="nutritrace-2fa-otp-input" id="otp-3" />
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                class="nutritrace-2fa-otp-input" id="otp-4" />
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                class="nutritrace-2fa-otp-input" id="otp-5" />
                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                class="nutritrace-2fa-otp-input" id="otp-6" />
                        </div>

                        <input type="hidden" name="code" id="otp-code" />
                        @error('code') <span class="nutritrace-login-error">{{ $message }}</span> @enderror
                    </div>

                    <!-- Timer + resend -->
                    <div class="nutritrace-2fa-resend">
                        <span class="nutritrace-2fa-timer" id="otp-timer">Renvoyer le code dans 00:60</span>
                        <button type="button" class="nutritrace-2fa-resend-btn" id="resend-btn" disabled>
                            Renvoyer le code
                        </button>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="nutritrace-login-submit">
                        Vérifier
                    </button>

                    <!-- Back to login -->
                    <div class="nutritrace-login-register">
                        <a href="{{ route('login') }}">Retour à la connexion</a>
                    </div>
                </form>

            </div>
        </section>

    </main>

    <script>
        const inputs = Array.from(document.querySelectorAll('.nutritrace-2fa-otp-input'));
        const hiddenField = document.getElementById('otp-code');
        const form = document.querySelector('.nutritrace-login-form');
        const timerEl = document.getElementById('otp-timer');
        const resendBtn = document.getElementById('resend-btn');

        function updateHiddenField() {
            hiddenField.value = inputs.map(i => i.value).join('');
        }

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateHiddenField();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');

                pasted.split('').slice(0, inputs.length).forEach((char, i) => {
                    if (inputs[i]) inputs[i].value = char;
                });

                const next = inputs[Math.min(pasted.length, inputs.length - 1)];
                if (next) next.focus();

                updateHiddenField();
            });
        });

        form.addEventListener('submit', updateHiddenField);

        // Resend countdown
        let seconds = 60;

        function tick() {
            seconds--;

            if (seconds <= 0) {
                timerEl.style.display = 'none';
                resendBtn.disabled = false;
                return;
            }

            const mm = String(Math.floor(seconds / 60)).padStart(2, '0');
            const ss = String(seconds % 60).padStart(2, '0');
            timerEl.textContent = `Renvoyer le code dans ${mm}:${ss}`;
            setTimeout(tick, 1000);
        }

        setTimeout(tick, 1000);

        resendBtn.addEventListener('click', async () => {
            resendBtn.disabled = true;

            try {
                const response = await fetch("{{ route('2fa.resend') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) throw new Error('Resend failed');

                inputs.forEach(input => input.value = '');
                inputs[0].focus();
                updateHiddenField();

                timerEl.style.display = 'inline';
                seconds = 60;
                setTimeout(tick, 1000);
            } catch (err) {
                resendBtn.disabled = false;
                alert("Une erreur est survenue lors de l'envoi du code. Réessayez.");
            }
        });
    </script>

</body>

</html>