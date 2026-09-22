<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <!-- SideBar -->
    @include('components.sidebar')

    <div class="nutritrace-main-container">
        <div class="nutritrace-navbar">
            <!-- Search -->
            <div class="nutritrace-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="text" placeholder="Rechercher un produit, un lot, un acteur...">
            </div>

            <!-- Right side -->
            <div class="nutritrace-navbar-right">
                <!-- Notifications -->
                <div class="nutritrace-notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="notification-badge">3</span>
                </div>

                <!-- Profile -->
                <div class="nutritrace-profile" id="profileTrigger">
                    <div class="nutritrace-profile-avatar">
                        <img src="{{ asset('images/avatar.png') }}" class="avatar-image" alt="Photo de profil">
                    </div>
                    <div class="nutritrace-profile-info">
                        <span class="profile-name">{{ $user->fullname ?? 'User' }}</span>
                        <span class="profile-role">
                            {{ $user->roles->isNotEmpty() ? ucfirst($user->roles->first()->name) : 'Utilisateur' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="nutritrace-content">
            <h1>Produits</h1>
            <p>Page des produits - Contenu à venir</p>
        </div>
    </div>

</body>

</html>
