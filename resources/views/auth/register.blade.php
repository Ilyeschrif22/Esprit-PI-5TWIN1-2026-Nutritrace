@extends('layouts.app')

@section('title', 'Inscription — '.config('app.name'))

@section('content')
    <div class="panel">
        <h1>Créer un compte</h1>
        <p class="subtitle">Profil tunisien — CIN, téléphone et gouvernorat requis.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid">
                <div class="field">
                    <label for="prenom">Prénom</label>
                    <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required autofocus>
                    @error('prenom') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="name">Nom</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="cin">CIN (8 chiffres)</label>
                    <input id="cin" type="text" name="cin" value="{{ old('cin') }}" required maxlength="8" inputmode="numeric">
                    @error('cin') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="telephone">Téléphone</label>
                    <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}" required placeholder="2xxxxxxx ou +2162xxxxxxx">
                    @error('telephone') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="date_naissance">Date de naissance</label>
                    <input id="date_naissance" type="date" name="date_naissance" value="{{ old('date_naissance') }}">
                    @error('date_naissance') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="genre">Genre</label>
                    <select id="genre" name="genre">
                        <option value="">—</option>
                        <option value="homme" @selected(old('genre') === 'homme')>Homme</option>
                        <option value="femme" @selected(old('genre') === 'femme')>Femme</option>
                    </select>
                    @error('genre') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="gouvernorat">Gouvernorat</label>
                    <select id="gouvernorat" name="gouvernorat" required>
                        <option value="">Choisir…</option>
                        @foreach ($gouvernorats as $gouvernorat)
                            <option value="{{ $gouvernorat }}" @selected(old('gouvernorat') === $gouvernorat)>{{ $gouvernorat }}</option>
                        @endforeach
                    </select>
                    @error('gouvernorat') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="delegation">Délégation</label>
                    <input id="delegation" type="text" name="delegation" value="{{ old('delegation') }}">
                    @error('delegation') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="ville">Ville</label>
                    <input id="ville" type="text" name="ville" value="{{ old('ville') }}">
                    @error('ville') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field full">
                    <label for="adresse">Adresse</label>
                    <input id="adresse" type="text" name="adresse" value="{{ old('adresse') }}">
                    @error('adresse') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="code_postal">Code postal</label>
                    <input id="code_postal" type="text" name="code_postal" value="{{ old('code_postal') }}" maxlength="4" inputmode="numeric">
                    @error('code_postal') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('login') }}">Déjà inscrit ? Se connecter</a>
                <button type="submit" class="btn">S'inscrire</button>
            </div>
        </form>
    </div>
@endsection
