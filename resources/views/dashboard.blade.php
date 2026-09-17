@section('title', 'Tableau de bord — ' . config('app.name'))

@section('content')
<div class="topbar">
    <div>
        <h1 style="margin:0;">Bonjour, {{ auth()->user()->nom_complet }}</h1>
        <p class="meta" style="margin:0.35rem 0 0;">
            {{ auth()->user()->gouvernorat }}
            @if (auth()->user()->cin)
                · CIN {{ auth()->user()->cin }}
            @endif
        </p>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-ghost">Déconnexion</button>
    </form>
</div>

<div class="panel">
    <p class="subtitle" style="margin-bottom:0;">
        Vous êtes connecté avec <strong>{{ auth()->user()->email }}</strong>
        @if (auth()->user()->telephone)
            · {{ auth()->user()->telephone }}
        @endif
    </p>
</div>