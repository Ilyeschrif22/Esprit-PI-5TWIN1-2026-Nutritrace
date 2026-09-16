@extends('layouts.app')

@section('title', 'Connexion — '.config('app.name'))

@section('content')
    <div class="panel" style="max-width: 420px; margin: 0 auto;">
        <h1>Connexion</h1>
        <p class="subtitle">Accédez à votre compte NutriTrace.</p>

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <label class="remember">
                <input type="checkbox" name="remember">
                Se souvenir de moi
            </label>

            <div class="actions">
                <a href="{{ route('register') }}">Créer un compte</a>
                <button type="submit" class="btn">Se connecter</button>
            </div>
        </form>
    </div>
@endsection
