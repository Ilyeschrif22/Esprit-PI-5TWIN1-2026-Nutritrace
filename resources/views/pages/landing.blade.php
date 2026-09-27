<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriTrace</title>

    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>

<body>

    <div class="nutritrace-landing-navbar">
        <div class="nutritrace-logo"><img src="{{ asset('images/logo.png') }}" class="nutritrace-logo"></div>

        <ul class="nutritrace-navbar-menu">

            <li class="nutritrace-navbar-item">
                <a href="{{ url('/') }}" class="nutritrace-navbar-link">
                    Accueil
                </a>
            </li>

            <li class="nutritrace-navbar-item">
                <a href="{{ url('/produits') }}" class="nutritrace-navbar-link">
                    Produits
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-down preview-icon">
                    <path d="m6 9 6 6 6-6" />
                </svg>

            </li>

            <li class="nutritrace-navbar-item">
                <a href="{{ url('/tracabilite') }}" class="nutritrace-navbar-link">
                    Traçabilité
                </a>
            </li>

            <li class="nutritrace-navbar-item">
                <a href="{{ url('/certifications') }}" class="nutritrace-navbar-link">
                    Certifications
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-down preview-icon">
                    <path d="m6 9 6 6 6-6" />
                </svg>

            </li>

            <li class="nutritrace-navbar-item">
                <a href="{{ url('/impact-environnemental') }}" class="nutritrace-navbar-link">
                    Impact environnemental
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-down preview-icon">
                    <path d="m6 9 6 6 6-6" />
                </svg>

            </li>

            <li class="nutritrace-navbar-item">
                <a href="{{ url('/a-propos') }}" class="nutritrace-navbar-link">
                    À propos
                </a>
            </li>

        </ul>

        <div class="nav-bar-actions">

            <!-- Search -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-search preview-icon">
                <path d="m21 21-4.34-4.34" />
                <circle cx="11" cy="11" r="8" />
            </svg>

            <!-- Heart -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-heart preview-icon">
                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
            </svg>

            <!-- Shopping bag -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-shopping-bag preview-icon">
                <path d="M16 10a4 4 0 0 1-8 0" />
                <path d="M3.103 6.034h17.794" />
                <path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z" />
            </svg>

            <!-- Profile -->
            <div class="nutritrace-profile">

                <button type="button" class="nutritrace-profile-button">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="lucide lucide-user-round preview-icon">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                </button>

            <div class="nutritrace-profile-dropdown">
                <a href="{{ route('register') }}" class="register-button">
                    <span class="inscription">Inscription</span>
                </a>
            
                <a href="{{ route('login') }}" class="login-button">
                    Connexion
                </a>
            </div>

            </div>

        </div>

    </div>

    <div class="landing-container">
        <div class="hero-section">

            <div class="hero-title">Systèmes Agricoles<br /> Durables et Innovants</div>

            <div>
                <p class="nutritrace-desctiption">
                    Suivez le parcours de vos produits alimentaires,<br />
                    découvrez leur impact environnemental et
                    faites des choix éclairés<br /> grâce à NutriTrace.
                </p>
                <div class="explore-buttons">
                    <div class="explore-services">Explorer NutriTrace <svg width="24px" height="24px" viewBox="0 0 24 24"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z"
                                    fill="#000000"></path>
                            </g>
                        </svg></div>
                    <div class="learn-more">Plus de details<svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M13.2328 16.4569C12.9328 16.7426 12.9212 17.2173 13.2069 17.5172C13.4926 17.8172 13.9673 17.8288 14.2672 17.5431L13.2328 16.4569ZM19.5172 12.5431C19.8172 12.2574 19.8288 11.7827 19.5431 11.4828C19.2574 11.1828 18.7827 11.1712 18.4828 11.4569L19.5172 12.5431ZM18.4828 12.5431C18.7827 12.8288 19.2574 12.8172 19.5431 12.5172C19.8288 12.2173 19.8172 11.7426 19.5172 11.4569L18.4828 12.5431ZM14.2672 6.4569C13.9673 6.17123 13.4926 6.18281 13.2069 6.48276C12.9212 6.78271 12.9328 7.25744 13.2328 7.5431L14.2672 6.4569ZM19 12.75C19.4142 12.75 19.75 12.4142 19.75 12C19.75 11.5858 19.4142 11.25 19 11.25V12.75ZM5 11.25C4.58579 11.25 4.25 11.5858 4.25 12C4.25 12.4142 4.58579 12.75 5 12.75V11.25ZM14.2672 17.5431L19.5172 12.5431L18.4828 11.4569L13.2328 16.4569L14.2672 17.5431ZM19.5172 11.4569L14.2672 6.4569L13.2328 7.5431L18.4828 12.5431L19.5172 11.4569ZM19 11.25L5 11.25V12.75L19 12.75V11.25Z"
                                    fill="#ffffff"></path>
                            </g>
                        </svg></div>
                </div>
            </div>

        </div>
    </div>


    <script src="{{ asset('js/landing.js') }}"></script>
</body>

</html>