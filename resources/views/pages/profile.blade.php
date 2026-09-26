<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body>
    @include('components.sidebar')

    <div class="nutritrace-main-container">
        @include('components.navbar')

        <div class="nutritrace-content">

            <!-- <div class="profile-header">
                <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="6" r="4" stroke="#05342d" stroke-width="1.5"></circle>
                    <path d="M19.9975 18C20 17.8358 20 17.669 20 17.5C20 15.0147 16.4183 13 12 13C7.58172 13 4 15.0147 4 17.5C4 19.9853 4 22 12 22C14.231 22 15.8398 21.8433 17 21.5634"
                        stroke="#05342d" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
                <div>
                    <h1 class="profile-title">Mon profil</h1>
                    <p class="profile-subtitle">Gérez vos informations personnelles et vos préférences de compte.</p>
                </div>
            </div> -->

            <div class="profile-container">
<div class="left-profile-info">
    <div class="left-profile-top">
        <div class="profile-avatar-wrapper">
            <img class="profile-image-avatar" src="{{ asset('images/avatar.png') }}">
            <div class="profile-avatar-camera">
                <svg width="14px" height="14px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 8h2l2-3h8l2 3h2a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z"
                        stroke="#fff" stroke-width="1.5" stroke-linejoin="round"></path>
                    <circle cx="12" cy="13" r="3.5" stroke="#fff" stroke-width="1.5"></circle>
                </svg>
            </div>
        </div>
    </div>

    <div class="left-profile-bottom">
        <div class="profile-card-name">{{ $user->fullname }}</div>
        <div class="profile-role">
            {{ $user->roles->isNotEmpty() ? ucfirst($user->roles->first()->name) : 'Utilisateur' }}
        </div>
        <div class="compte-actif">
            <span class="status-dot"></span> Compte actif
        </div>
    </div>

    <div class="left-profile-divider"></div>

    <nav class="left-profile-nav">
        <a href="#" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="6" r="4"></circle>
                <path d="M19.9975 18C20 17.8358 20 17.669 20 17.5C20 15.0147 16.4183 13 12 13C7.58172 13 4 15.0147 4 17.5C4 19.9853 4 22 12 22C14.231 22 15.8398 21.8433 17 21.5634"></path>
            </svg>
            Informations personnelles
        </a>
        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            Sécurité
        </a>
        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            Préférences
        </a>
        <a href="#" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
            </svg>
            Notifications
        </a>
    </nav>
</div>

                <div class="right-profile-info">

                    <div class="info-pers">
                        <div class="info-pers-header">
                            <div>
                                <div class="profile-title-description">
                                    <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="6" r="4" stroke="#05342d" stroke-width="1.5"></circle>
                                        <path
                                            d="M19.9975 18C20 17.8358 20 17.669 20 17.5C20 15.0147 16.4183 13 12 13C7.58172 13 4 15.0147 4 17.5C4 19.9853 4 22 12 22C14.231 22 15.8398 21.8433 17 21.5634"
                                            stroke="#05342d" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                    Informations personnelles
                                </div>
                                <p class="info-pers-subtitle">Modifiez vos informations de profil.</p>
                            </div>
                            <button type="button" class="btn-modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="btn-modifier-icon">
                                    <path
                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                    <path d="m15 5 4 4" />
                                </svg>
                                Modifier
                            </button>
                        </div>

                        <form class="info-form-grid">
                            <div class="form-field">
                                <label>Nom complet <span class="required">*</span></label>
                                <div class="form-field-input">
                                    <input type="text" value="{{ $user->fullname }}" readonly>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Rôle</label>
                                <div class="form-field-input">
                                    <select disabled>
                                        <option>
                                            {{ $user->roles->isNotEmpty() ? ucfirst($user->roles->first()->name) : 'Utilisateur' }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Email <span class="required">*</span></label>
                                <div class="form-field-input">
                                    <input type="email" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Entreprise / Organisation</label>
                                <div class="form-field-input">
                                    <input type="text" value="{{ $user->organisation ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Téléphone</label>
                                <div class="form-field-input">
                                    <input type="text" value="{{ $user->phone ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Localisation</label>
                                <div class="form-field-input">
                                    <input type="text" value="{{ $user->location ?? '' }}" readonly>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="photo-profile">
                        <div>
                            <div class="profile-title-description">
                                <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="4" width="18" height="16" rx="2" stroke="#05342d" stroke-width="1.5">
                                    </rect>
                                    <circle cx="8" cy="9" r="1.5" fill="#05342d"></circle>
                                    <path d="M4 17l5-5 4 4 3-3 4 4" stroke="#05342d" stroke-width="1.5"></path>
                                </svg>
                                Photo de profil
                            </div>
                        </div>

                        <div class="photo-profile-body">
                            <img class="photo-preview" src="{{ asset('images/avatar.png') }}">
                            <label class="photo-dropzone">
                                <input type="file" accept=".jpg,.jpeg,.png" style="display:none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                    fill="none" stroke="#05342d" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-cloud-upload preview-icon">
                                    <path d="M12 13v8" />
                                    <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                    <path d="m8 17 4-4 4 4" />
                                </svg>
                                Choisir une photo
                                <small>JPG, PNG (max. 2 Mo)</small>
                            </label>
                           <button type="button" class="btn-supprimer-photo">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-supprimer-photo-icon">
        <path d="M3 6h18"/>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
        <line x1="10" y1="11" x2="10" y2="17"/>
        <line x1="14" y1="11" x2="14" y2="17"/>
    </svg>
    Supprimer la photo
</button>
                        </div>
                    </div>

                    <div class="compte-info">
                        <div>
                            <div class="profile-title-description">
                                <svg width="28px" height="28px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2l7 3v6c0 5-3.5 8-7 9-3.5-1-7-4-7-9V5l7-3z" stroke="#05342d"
                                        stroke-width="1.5"></path>
                                </svg>
                                Informations du compte
                            </div>
                            <p class="compte-info-subtitle">Ces informations sont liées à votre compte et ne peuvent
                                être modifiées que par votre administrateur.</p>
                        </div>

                        <div class="compte-info-row">
                            <div class="compte-info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#05342d" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-calendar-clock preview-icon">
                                    <path d="M16 14v2.2l1.6 1" />
                                    <path d="M16 2v3" />
                                    <path d="M21 7.338V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h2.338" />
                                    <path d="M3 9h5.859" />
                                    <path d="M8 2v3" />
                                    <circle cx="16" cy="16" r="6" />
                                </svg>
                                <div>
                                    <span class="label">Date de création</span>
                                    <span class="value">{{ $user->created_at->format('d M. Y') }}</span>
                                </div>
                            </div>
                            <div class="compte-info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#05342d" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-rotate-cw-fading-clock preview-icon">
                                    <path d="M12 3a9.75 9.75 0 0 1 6.74 2.74" />
                                    <path d="M18.74 5.74 21 8" />
                                    <path d="M21 8V3" />
                                    <path d="M7.5 19.794c-6-3.464-6-12.124 0-15.588" />
                                    <path d="M7.5 4.206A9 9 0 0 1 12 3" />
                                    <path d="M12 7v5l4 2" />
                                    <path d="M14 20.775A9 9 0 0 1 12 21" />
                                    <path d="M19 17.656a9 9 0 0 1-1.5 1.456" />
                                    <path d="M21 12a9 9 0 0 1-.228 2" />
                                    <path d="M21 8h-5" />
                                </svg>
                                <div>
                                    <span class="label">Dernière connexion</span>
                                    <span
                                        class="value">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Aujourd\'hui' }}</span>
                                </div>
                            </div>
                            <div class="compte-info-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="#05342d" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-activity preview-icon">
                                    <path
                                        d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />
                                </svg>
                                <div>
                                    <span class="label">Statut du compte</span>
                                    <span class="value">Actif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>