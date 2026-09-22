<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                    <span class="profile-name">{{ $user->fullname }}</span>
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
    <div class="nutritrace-dashboard-content">

        <!-- Dashboard Header -->
        <div class="dashboard-header">

            <div>
                <h1>Bonjour {{ $user->fullname }}</h1>
                <p>Voici un aperçu de l'activité sur votre plateforme NutriTrace.</p>
            </div>

            <div class="dashboard-date">
                <div class="date-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4" />
                        <path d="M16 2v4" />
                        <rect width="18" height="18" x="3" y="4" rx="2" />
                        <path d="M3 10h18" />
                    </svg>
                </div>

                <div>
                    <strong>16 septembre 2026</strong>
                    <span>Dernière mise à jour : 10:24</span>
                </div>
            </div>

        </div>


        <!-- Statistics Cards -->
        <div class="dashboard-stats">


            <!-- Produits -->
            <div class="stat-card">

                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21 16-9 5-9-5V8l9-5 9 5z" />
                        <path d="m3.3 7 8.7 5 8.7-5" />
                        <path d="M12 22V12" />
                    </svg>
                </div>

                <span class="stat-title">Produits</span>

                <span class="stat-number">1 245</span>

                <span class="stat-change positive">
                    ↑ +12%
                </span>

                <span class="stat-period">vs mois dernier</span>

            </div>


            <!-- Lots -->
            <div class="stat-card">

                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12 3-8 4 8 4 8-4-8-4Z" />
                        <path d="m4 12 8 4 8-4" />
                        <path d="m4 17 8 4 8-4" />
                    </svg>
                </div>

                <span class="stat-title">Lots</span>

                <span class="stat-number">582</span>

                <span class="stat-change positive">
                    ↑ +8%
                </span>

                <span class="stat-period">vs mois dernier</span>

            </div>


            <!-- Acteurs -->
            <div class="stat-card">

                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>

                <span class="stat-title">Acteurs</span>

                <span class="stat-number">124</span>

                <span class="stat-change positive">
                    ↑ +15%
                </span>

                <span class="stat-period">vs mois dernier</span>

            </div>


            <!-- Certifications -->
            <div class="stat-card">

                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </div>

                <span class="stat-title">Certifications</span>

                <span class="stat-number">87</span>

                <span class="stat-change positive">
                    ↑ +10%
                </span>

                <span class="stat-period">vs mois dernier</span>

            </div>


            <!-- Transports -->
            <div class="stat-card">

                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 17h4V5H2v12h3" />
                        <path d="M14 8h4l4 4v5h-3" />
                        <circle cx="7.5" cy="17.5" r="2.5" />
                        <circle cx="16.5" cy="17.5" r="2.5" />
                    </svg>
                </div>

                <span class="stat-title">Transports</span>

                <span class="stat-number">1 832</span>

                <span class="stat-change positive">
                    ↑ +18%
                </span>

                <span class="stat-period">vs mois dernier</span>

            </div>


            <!-- Émissions CO2 -->
            <div class="stat-card">

                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
                    </svg>
                </div>

                <span class="stat-title">Émissions CO₂</span>

                <span class="stat-number">4,2 t</span>

                <span class="stat-change negative">
                    ↓ -6%
                </span>

                <span class="stat-period">vs mois dernier</span>

            </div>

        </div>


        <!-- Row 2: category donut / lots trend / lot traceability map -->
        <div class="insights-grid">

            <!-- Produits par catégorie -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3>Produits par catégorie</h3>
                </div>

                <div class="donut-wrap">
                    <div class="donut category-donut">
                        <div class="donut-center">
                            <strong>1 245</strong>
                            <span>produits</span>
                        </div>
                    </div>

                    <ul class="donut-legend">
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#1a5632"></span>
                            <span class="legend-label">Fruits</span>
                            <span class="legend-value">28%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#2e7d32"></span>
                            <span class="legend-label">Légumes</span>
                            <span class="legend-value">24%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#3fa796"></span>
                            <span class="legend-label">Céréales</span>
                            <span class="legend-value">15%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#6ec6c2"></span>
                            <span class="legend-label">Produits laitiers</span>
                            <span class="legend-value">12%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#a9dfd8"></span>
                            <span class="legend-label">Viandes</span>
                            <span class="legend-value">8%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#dce7e4"></span>
                            <span class="legend-label">Autres</span>
                            <span class="legend-value">13%</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Évolution des lots -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3>Évolution des lots</h3>
                    <button type="button" class="chart-action-chip">
                        6 derniers mois
                    </button>
                </div>

                <div class="lots-chart">
                </div>
            </div>

            <!-- Traçabilité du lot -->
            <div class="chart-card traceability-map-card">
                <div class="chart-card-header">
                    <h3>Traçabilité du lot #LOT-2026-001</h3>
                    <button type="button" class="chart-action-chip primary">
                        Voir la carte complète
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </button>
                </div>

             <div class="traceability-map-visual">
    <div id="traceability-map"></div>
</div>
            </div>

        </div>


        <!-- Row 3: actors bar chart / lot status / environmental impact / recent activity -->
        <div class="secondary-grid">

            <!-- Acteurs par type -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3>Acteurs par type</h3>
                </div>

                
            </div>

            <!-- Statut des lots -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3>Statut des lots</h3>
                </div>

                <div class="donut-wrap">
                    <div class="donut status-donut">
                        <div class="donut-center">
                            <strong>582</strong>
                            <span>lots</span>
                        </div>
                    </div>

                    <ul class="donut-legend">
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#2e7d32"></span>
                            <span class="legend-label">En cours</span>
                            <span class="legend-value">52%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#c65a4e"></span>
                            <span class="legend-label">Expiré</span>
                            <span class="legend-value">18%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#3fa796"></span>
                            <span class="legend-label">Complet</span>
                            <span class="legend-value">22%</span>
                        </li>
                        <li class="donut-legend-item">
                            <span class="legend-dot" style="background:#d7e0df"></span>
                            <span class="legend-label">En attente</span>
                            <span class="legend-value">8%</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Impact environnemental -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3>Impact environnemental</h3>
                </div>

            </div>

            <!-- Activité récente -->
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3>Activité récente</h3>
                    <a href="#" class="chart-action-chip">Voir tout</a>
                </div>

                <ul class="activity-list">
                    <li class="activity-row">
                        <div class="activity-icon icon-package">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                                <path d="m3.3 7 8.7 5 8.7-5" />
                                <path d="M12 22V12" />
                            </svg>
                        </div>
                        <div class="activity-info">
                            <strong>Un nouveau lot a été ajouté</strong>
                            <span>LOT-2026-001 – Tomate</span>
                        </div>
                        <span class="activity-time">10:24</span>
                    </li>

                    <li class="activity-row">
                        <div class="activity-icon icon-shield">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </div>
                        <div class="activity-info">
                            <strong>Certification BIO vérifiée</strong>
                            <span>LOT-2026-001</span>
                        </div>
                        <span class="activity-time">09:47</span>
                    </li>

                    <li class="activity-row">
                        <div class="activity-icon icon-truck">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                                <path d="M15 18H9" />
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                                <circle cx="17" cy="18" r="2" />
                                <circle cx="7" cy="18" r="2" />
                            </svg>
                        </div>
                        <div class="activity-info">
                            <strong>Transport enregistré</strong>
                            <span>Nabeul → Tunis</span>
                        </div>
                        <span class="activity-time">08:32</span>
                    </li>

                    <li class="activity-row">
                        <div class="activity-icon icon-user">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="8" r="5" />
                                <path d="M20 21a8 8 0 0 0-16 0" />
                            </svg>
                        </div>
                        <div class="activity-info">
                            <strong>Un utilisateur a mis à jour un produit</strong>
                            <span>Huile d'olive</span>
                        </div>
                        <span class="activity-time">07:15</span>
                    </li>

                    <li class="activity-row">
                        <div class="activity-icon icon-file">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M10 9H8" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                            </svg>
                        </div>
                        <div class="activity-info">
                            <strong>Document ajouté</strong>
                            <span>Certificat d'origine</span>
                        </div>
                        <span class="activity-time">06:52</span>
                    </li>
                </ul>
            </div>

        </div>


        <!-- Footer -->
        <div class="dashboard-footer">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
            </svg>
            <strong>NutriTrace</strong>
            <span class="footer-divider">|</span>
            <span>Plus de transparence pour une alimentation durable</span>
        </div>


    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="./script.js"></script>
<script>
    (function () {
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
<script>
    (function () {
        var canvas = document.getElementById('lotsChart');
        if (!canvas || typeof Chart === 'undefined') return;

        var labels = canvas.dataset.labels.split(',');
        var values = canvas.dataset.values.split(',').map(Number);

        var ctx = canvas.getContext('2d');
        var gradient = ctx.createLinearGradient(0, 0, 0, canvas.clientHeight || 260);
        gradient.addColorStop(0, 'rgba(46, 125, 50, 0.22)');
        gradient.addColorStop(1, 'rgba(46, 125, 50, 0)');

        var lastIndex = values.length - 1;
        var pointRadii = values.map(function (_, i) { return i === lastIndex ? 5 : 4; });
        var pointColors = values.map(function (_, i) { return i === lastIndex ? '#2e7d32' : '#ffffff'; });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    borderColor: '#2e7d32',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: pointRadii,
                    pointHoverRadius: pointRadii.map(function (r) { return r + 1; }),
                    pointBackgroundColor: pointColors,
                    pointBorderColor: '#2e7d32',
                    pointBorderWidth: 2.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#163c45',
                        titleFont: { family: 'Inter', size: 12, weight: '600' },
                        bodyFont: { family: 'Inter', size: 13, weight: '600' },
                        padding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            label: function (item) { return item.formattedValue + ' lots'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 14, weight: '600' },
                            color: '#84969b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 800,
                        ticks: {
                            stepSize: 200,
                            font: { family: 'Inter', size: 15, weight: '600' },
                            color: '#84969b'
                        },
                        grid: { color: '#edf2f1' }
                    }
                }
            }
        });
    })();

    document.addEventListener("DOMContentLoaded", function () {

        const locations = [
            [36.8065, 10.1815],
            [36.8180, 10.1658],
            [36.8320, 10.1850],
            [36.8450, 10.1950]
        ];

        const map = L.map("traceability-map", {
            zoomControl: true
        }).setView([36.825, 10.18], 12);

        L.tileLayer(
            "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            {
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }
        ).addTo(map);

        // Custom green pointer
        const greenIcon = L.divIcon({
            className: "custom-map-marker",
            html: `
                <div style="
                    width: 32px;
                    height: 32px;
                    background: #2e7d32;
                    border: 3px solid white;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <span style="
                        transform: rotate(45deg);
                        color: white;
                        font-weight: 700;
                        font-size: 13px;
                    "></span>
                </div>
            `,
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Add 4 markers
        locations.forEach((location, index) => {

            const icon = L.divIcon({
                className: "custom-map-marker",
                html: `
                    <div style="
                        width: 32px;
                        height: 32px;
                        background: #2e7d32;
                        border: 3px solid white;
                        border-radius: 50% 50% 50% 0;
                        transform: rotate(-45deg);
                        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <span style="
                            transform: rotate(45deg);
                            color: white;
                            font-weight: 700;
                            font-size: 13px;
                        ">${index + 1}</span>
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            L.marker(location, {
                icon: icon
            })
            .addTo(map)
            .bindPopup(`
                <strong>Lot ${index + 1}</strong><br>
                Point de traçabilité ${index + 1}
            `);
        });

        // Connect the 4 points
        L.polyline(locations, {
            color: "#2e7d32",
            weight: 4,
            opacity: 0.85
        }).addTo(map);

        // Fit map to all markers
        map.fitBounds(locations, {
            padding: [40, 40]
        });

    });
</script>
</body>

</html>