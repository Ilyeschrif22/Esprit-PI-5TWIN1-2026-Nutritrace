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

            <!-- Dropdown arrow -->
            <svg class="profile-arrow" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m6 9 6 6 6-6"></path>
            </svg>

            <!-- Dropdown menu -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-dropdown-header">
                    <div class="profile-dropdown-avatar">
                        <img src="{{ asset('images/avatar.png') }}" class="avatar-image" alt="Photo de profil">
                    </div>
                    <div class="profile-dropdown-header-info">
                        <span class="dropdown-name">{{ $user->fullname }}</span>
                        <span class="dropdown-email">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="profile-dropdown-divider"></div>

                <ul class="profile-dropdown-list">
                    <li class="profile-dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="8" r="5" />
                            <path d="M20 21a8 8 0 0 0-16 0" />
                        </svg>
                        Voir le profil
                    </li>

                    <li class="profile-dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        Paramètres
                    </li>

                    <div class="profile-dropdown-divider"></div>

                    <li class="profile-dropdown-item">
                        <form method="POST" action="{{ route('logout') }}" class="profile-dropdown-logout">
                            @csrf
                            <button type="submit" class="profile-dropdown-item-btn danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        // Only run if this is not the first load or if elements exist
        var trigger = document.getElementById('profileTrigger');
        var dropdown = document.getElementById('profileDropdown');

        if (!trigger || !dropdown) return;

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            trigger.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!trigger.contains(e.target)) {
                trigger.classList.remove('open');
            }
        });

        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    })();
</script>
