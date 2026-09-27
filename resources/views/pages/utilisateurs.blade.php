<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs | NutriTrace</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/utilisateurs.css') }}">
</head>

<body>
    @include('components.sidebar')

    <main class="nutritrace-main-container">
        @include('components.navbar')

        <section class="nutritrace-content users-page">
            <header class="users-heading">
                <div>
                    <span class="users-eyebrow">Administration</span>
                    <h1><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" /></svg>Utilisateurs</h1>
                    <p>Gérez les comptes et les accès de la plateforme NutriTrace.</p>
                </div>
                <a class="users-primary-button" href="{{ route('utilisateurs.create') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" /></svg>
                    Ajouter un utilisateur
                </a>
            </header>

            @if (session('status'))
                <div class="users-alert users-alert--success" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="users-alert users-alert--error" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <section class="users-summary" aria-label="Résumé des utilisateurs">
                <article class="users-summary-card">
                    <span class="summary-icon summary-icon--green"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM20 8v6m3-3h-6" /></svg></span>
                    <span class="summary-label">Total des utilisateurs</span>
                    <strong>{{ number_format($totalUsers, 0, ',', ' ') }}</strong>
                    <span class="summary-note">Comptes enregistrés</span>
                </article>
                <article class="users-summary-card">
                    <span class="summary-icon summary-icon--blue"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 7 10 17l-5-5" /><path d="M21 12a9 9 0 1 1-5.3-8.2" /></svg></span>
                    <span class="summary-label">Actifs</span>
                    <strong>{{ number_format($activeUsers, 0, ',', ' ') }}</strong>
                    <span class="summary-note">Accès autorisé</span>
                </article>
                <article class="users-summary-card">
                    <span class="summary-icon summary-icon--red"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="m15 9-6 6m0-6 6 6" /></svg></span>
                    <span class="summary-label">Inactifs</span>
                    <strong>{{ number_format($inactiveUsers, 0, ',', ' ') }}</strong>
                    <span class="summary-note">Accès suspendu</span>
                </article>
                <article class="users-summary-card">
                    <span class="summary-icon summary-icon--teal"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" /><path d="m9 12 2 2 4-4" /></svg></span>
                    <span class="summary-label">Administrateurs</span>
                    <strong>{{ number_format($adminUsers, 0, ',', ' ') }}</strong>
                    <span class="summary-note">Comptes avec accès admin</span>
                </article>
                <article class="users-summary-card">
                    <span class="summary-icon summary-icon--mint"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" /></svg></span>
                    <span class="summary-label">Utilisateurs partenaires</span>
                    <strong>{{ number_format($partnerUsers, 0, ',', ' ') }}</strong>
                    <span class="summary-note">Producteurs et acteurs</span>
                </article>
            </section>

            <section class="users-directory" aria-label="Liste des utilisateurs">
                <form class="users-filters" method="GET" action="{{ route('utilisateurs') }}">
                    <label class="users-search-field">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Rechercher un utilisateur, un nom, un email..." aria-label="Rechercher un utilisateur">
                    </label>
                    <label class="filter-select">
                        <span class="sr-only">Filtrer par rôle</span>
                        <select name="role">
                            <option value="">Tous les rôles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(($filters['role'] ?? '') === $role)>{{ match ($role) { 'admin' => 'Administrateur', 'producteur' => 'Producteur', 'transformateur' => 'Transformateur', 'distributeur' => 'Distributeur', 'consommateur' => 'Consommateur', default => ucfirst($role) } }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="filter-select">
                        <span class="sr-only">Filtrer par statut</span>
                        <select name="status">
                            <option value="">Tous les statuts</option>
                            <option value="active" @selected(($filters['status'] ?? '') === 'active')>Actif</option>
                            <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactif</option>
                        </select>
                    </label>
                    <label class="filter-date">
                        <span class="sr-only">Inscrit depuis</span>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" aria-label="Inscrit depuis">
                    </label>
                    <button class="users-filter-submit" type="submit">Filtrer</button>
                    <a class="users-filter-reset" href="{{ route('utilisateurs') }}" aria-label="Réinitialiser les filtres">Réinitialiser</a>
                </form>

                <div class="users-table-wrap">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th scope="col">Utilisateur</th>
                                <th scope="col">Email</th>
                                <th scope="col">Rôle</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Inscription</th>
                                <th scope="col" class="users-actions-heading">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $rowUser)
                                @php
                                    $roleName = $rowUser->roles->first()?->name;
                                    $roleLabel = match ($roleName) {
                                        'admin' => 'Administrateur',
                                        'producteur' => 'Producteur',
                                        'transformateur' => 'Transformateur',
                                        'distributeur' => 'Distributeur',
                                        'consommateur' => 'Consommateur',
                                        default => ucfirst($roleName ?? 'Sans rôle'),
                                    };
                                    $initials = collect(explode(' ', $rowUser->fullname))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
                                @endphp
                                <tr>
                                    <td>
                                        <div class="user-identity">
                                            <span class="user-avatar">{{ $initials }}</span>
                                            <span class="user-name">{{ $rowUser->fullname }}</span>
                                        </div>
                                    </td>
                                    <td class="user-email">{{ $rowUser->email }}</td>
                                    <td><span class="user-role-badge user-role-badge--{{ \Illuminate\Support\Str::slug($roleName ?? 'sans-role') }}">{{ $roleLabel }}</span></td>
                                    <td><span class="user-status {{ $rowUser->is_active ? 'user-status--active' : 'user-status--inactive' }}"><span></span>{{ $rowUser->is_active ? 'Actif' : 'Inactif' }}</span></td>
                                    <td class="user-date">{{ $rowUser->created_at?->format('d M Y') ?? '—' }}</td>
                                    <td>
                                        <div class="users-row-actions">
                                            <a class="icon-button" href="{{ route('utilisateurs.show', $rowUser) }}" aria-label="Voir {{ $rowUser->fullname }}" title="Voir">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" /><circle cx="12" cy="12" r="3" /></svg>
                                            </a>
                                            <a class="icon-button" href="{{ route('utilisateurs.edit', $rowUser) }}" aria-label="Modifier {{ $rowUser->fullname }}" title="Modifier">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 5 4 4M4 20l4-.8L19 8a2.1 2.1 0 0 0-3-3L5 16l-1 4Z" /></svg>
                                            </a>
                                            <form method="POST" action="{{ route('utilisateurs.status', $rowUser) }}" onsubmit="return confirm('{{ $rowUser->is_active ? 'Désactiver ce compte ?' : 'Réactiver ce compte ?' }}')">
                                                @csrf
                                                @method('PATCH')
                                                <button class="icon-button {{ $rowUser->is_active ? 'status-toggle-button--deactivate' : 'status-toggle-button--activate' }}" type="submit" aria-label="{{ $rowUser->is_active ? 'Désactiver' : 'Activer' }} {{ $rowUser->fullname }}" title="{{ $rowUser->is_active ? 'Désactiver' : 'Activer' }}">
                                                    @if ($rowUser->is_active)
                                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5v14M15 5v14" /></svg>
                                                    @else
                                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m8 5 12 7-12 7V5Z" /></svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('utilisateurs.destroy', $rowUser) }}" onsubmit="return confirm('Supprimer le compte de {{ addslashes($rowUser->fullname) }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="icon-button icon-button--danger" type="submit" aria-label="Supprimer {{ $rowUser->fullname }}" title="Supprimer">
                                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2m3 0-1 14H6L5 6m4 4v6m6-6v6" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="users-empty" colspan="6">Aucun utilisateur ne correspond à ces filtres.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <footer class="users-table-footer">
                    <span>Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs</span>
                    {{ $users->links() }}
                </footer>
            </section>
        </section>
    </main>

    @if ($modal)
        <dialog class="users-dialog" open aria-labelledby="users-dialog-title">
            <div class="users-dialog-header">
                <div>
                    <span class="users-eyebrow">Gestion des comptes</span>
                    <h2 id="users-dialog-title">{{ $modal === 'create' ? 'Ajouter un utilisateur' : ($modal === 'edit' ? 'Modifier l’utilisateur' : 'Détails de l’utilisateur') }}</h2>
                </div>
                <form method="dialog"><button class="dialog-close" aria-label="Fermer">&times;</button></form>
            </div>

            @if ($modal === 'show')
                <dl class="user-details">
                    <div><dt>Nom complet</dt><dd>{{ $selectedUser->fullname }}</dd></div>
                    <div><dt>Email</dt><dd>{{ $selectedUser->email }}</dd></div>
                    <div><dt>Téléphone</dt><dd>{{ $selectedUser->phone ?: 'Non renseigné' }}</dd></div>
                    <div><dt>CIN</dt><dd>{{ $selectedUser->cin ?: 'Non renseigné' }}</dd></div>
                    <div><dt>Rôle</dt><dd>{{ ucfirst($selectedUser->roles->first()?->name ?? 'Sans rôle') }}</dd></div>
                    <div><dt>Statut</dt><dd>{{ $selectedUser->is_active ? 'Actif' : 'Inactif' }}</dd></div>
                    <div><dt>Créé le</dt><dd>{{ $selectedUser->created_at?->format('d/m/Y à H:i') ?? '—' }}</dd></div>
                </dl>
                <div class="users-dialog-actions">
                    <a class="users-secondary-button" href="{{ route('utilisateurs') }}">Fermer</a>
                    <a class="users-primary-button" href="{{ route('utilisateurs.edit', $selectedUser) }}">Modifier</a>
                </div>
            @else
                <form class="users-form" method="POST" action="{{ $modal === 'edit' ? route('utilisateurs.update', $selectedUser) : route('utilisateurs.store') }}">
                    @csrf
                    @if ($modal === 'edit')
                        @method('PUT')
                    @endif
                    <div class="users-form-grid">
                        <label><span>Nom complet <b>*</b></span><input name="fullname" value="{{ old('fullname', $selectedUser?->fullname) }}" required autocomplete="name"></label>
                        <label><span>Adresse email <b>*</b></span><input type="email" name="email" value="{{ old('email', $selectedUser?->email) }}" required autocomplete="email"></label>
                        <label><span>Téléphone</span><input name="phone" value="{{ old('phone', $selectedUser?->phone) }}" autocomplete="tel"></label>
                        <label><span>CIN</span><input name="cin" value="{{ old('cin', $selectedUser?->cin) }}" inputmode="numeric" maxlength="8"></label>
                        <label><span>Rôle <b>*</b></span>
                            <select name="role" required>
                                <option value="">Choisir un rôle</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" @selected(old('role', $selectedUser?->roles->first()?->name) === $role)>{{ match ($role) { 'admin' => 'Administrateur', 'producteur' => 'Producteur', 'transformateur' => 'Transformateur', 'distributeur' => 'Distributeur', 'consommateur' => 'Consommateur', default => ucfirst($role) } }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="users-status-field"><span>Statut du compte</span>
                            <input type="hidden" name="is_active" value="0">
                            <span class="users-checkbox"><input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $selectedUser?->exists ? $selectedUser->is_active : true))><span>Compte actif</span></span>
                        </label>
                        <label><span>{{ $modal === 'edit' ? 'Nouveau mot de passe' : 'Mot de passe' }}{{ $modal === 'create' ? ' *' : '' }}</span><input type="password" name="password" @required($modal === 'create') minlength="8" autocomplete="new-password"></label>
                        <label><span>Confirmer le mot de passe</span><input type="password" name="password_confirmation" @required($modal === 'create') minlength="8" autocomplete="new-password"></label>
                    </div>
                    @if ($modal === 'edit')
                        <p class="users-form-hint">Laissez les champs de mot de passe vides pour le conserver.</p>
                    @endif
                    <div class="users-dialog-actions">
                        <a class="users-secondary-button" href="{{ route('utilisateurs') }}">Annuler</a>
                        <button class="users-primary-button" type="submit">{{ $modal === 'edit' ? 'Enregistrer les modifications' : 'Créer le compte' }}</button>
                    </div>
                </form>
            @endif
        </dialog>
    @endif

    <script>
        const userDialog = document.querySelector('.users-dialog');
        if (userDialog?.open && typeof userDialog.showModal === 'function') {
            userDialog.removeAttribute('open');
            userDialog.showModal();
        }
    </script>
</body>
</html>
