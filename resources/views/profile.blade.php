<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil | NutriTrace</title>
    <style>
        :root { --ink:#073d3d; --muted:#6d8990; --line:#dce9ec; --green:#4eaa58; --soft:#e8f7e9; }
        * { box-sizing:border-box; } body { margin:0; background:#f4f9f9; color:var(--ink); font-family:Inter,ui-sans-serif,system-ui,sans-serif; }
        .page { display:flex; min-height:100vh; } .side { width:246px; padding:24px 14px; background:linear-gradient(180deg,#064642,#033c3b); color:#fff; } .brand { display:flex; align-items:center; gap:10px; padding:0 12px 28px; } .brand img { width:34px; height:42px; object-fit:contain; } .brand strong { font-size:18px; } .brand small { display:block; color:#74d67a; font-size:8px; font-weight:800; letter-spacing:.8px; }
        .nav { display:grid; gap:4px; } .nav a { display:flex; gap:12px; align-items:center; min-height:40px; padding:0 12px; border-radius:7px; color:#d4eeee; text-decoration:none; font-size:13px; } .nav a:hover,.nav a.active { background:#55ad59; color:#fff; } .nav-icon { width:18px; text-align:center; } .sustain { margin-top:44vh; padding:16px 14px; border:1px solid #4e957f; border-radius:8px; color:#d7eeee; font-size:11px; line-height:1.5; } .sustain strong { display:block; color:#fff; font-size:13px; }
        .main { flex:1; min-width:0; } .top { display:flex; align-items:center; min-height:64px; padding:0 30px; border-bottom:1px solid var(--line); background:#fff; } .search { width:min(510px,100%); height:36px; padding:0 14px 0 38px; border:1px solid var(--line); border-radius:6px; color:var(--muted); font-size:12px; } .top-user { margin-left:auto; display:flex; gap:9px; align-items:center; font-size:11px; } .avatar { display:grid; place-items:center; width:30px; height:30px; border-radius:50%; background:#e2eff0; font-size:16px; }
        .content { max-width:1080px; margin:auto; padding:28px 30px 40px; } h1 { margin:0 0 5px; font-size:28px; } .subtitle { margin:0 0 22px; color:var(--muted); font-size:12px; } .layout { display:grid; grid-template-columns:220px 1fr; gap:14px; } .card { border:1px solid var(--line); border-radius:8px; background:#fff; box-shadow:0 8px 26px rgba(16,73,73,.05); } .identity { overflow:hidden; text-align:center; } .identity-top { height:70px; background:linear-gradient(180deg,#e5f8e8,#f8fcf8); } .profile-avatar { display:grid; place-items:center; width:82px; height:82px; margin:-35px auto 8px; border:5px solid #d8f2dd; border-radius:50%; background:#bde5c8; color:#176b53; font-size:42px; } .identity h2 { margin:0; font-size:16px; } .identity p { margin:4px 0 14px; color:var(--muted); font-size:12px; } .status { display:inline-block; margin-bottom:20px; padding:5px 10px; border-radius:14px; background:var(--soft); color:#27833a; font-size:10px; font-weight:700; }
        .form-card { padding:20px; } .form-head { display:flex; align-items:center; justify-content:space-between; padding-bottom:16px; border-bottom:1px solid var(--line); } .form-head h2 { margin:0; font-size:16px; } .form-head p { margin:4px 0 0; color:var(--muted); font-size:11px; } .edit-label { color:var(--green); font-size:11px; font-weight:700; } .fields { display:grid; grid-template-columns:1fr 1fr; gap:16px; padding-top:18px; } label { display:grid; gap:7px; color:var(--ink); font-size:11px; font-weight:700; } input, select { width:100%; height:38px; padding:0 12px; border:1px solid var(--line); border-radius:6px; color:var(--ink); outline:0; background:#fff; font-size:12px; } input:focus,select:focus { border-color:var(--green); box-shadow:0 0 0 3px rgba(78,170,88,.12); } .error { color:#cf5148; font-size:10px; font-weight:400; } .actions { display:flex; justify-content:flex-end; align-items:center; gap:14px; margin-top:20px; padding-top:16px; border-top:1px solid var(--line); } .success { margin:0 auto 0 0; color:#27833a; font-size:11px; } .button { border:0; border-radius:6px; padding:11px 22px; background:#064642; color:#fff; font-size:12px; font-weight:700; } .back { color:var(--muted); font-size:11px; text-decoration:none; }
        @media (max-width:760px) { .page { display:block; } .side { width:100%; padding:12px; } .brand { padding-bottom:12px; } .nav { grid-template-columns:repeat(3,1fr); } .nav a { justify-content:center; font-size:10px; } .nav a span:last-child,.sustain { display:none; } .top { padding:12px 16px; } .top-user { display:none; } .content { padding:22px 16px; } .layout { grid-template-columns:1fr; } .fields { grid-template-columns:1fr; } }
    </style>
</head>
<body>
@php($user = auth()->user())
<div class="page">
    <aside class="side">
        <div class="brand"><img src="{{ asset('images/nutritrace-logo.png') }}" alt="NutriTrace"><div><strong>NutriTrace</strong><small>TRAÇABILITÉ ALIMENTAIRE</small></div></div>
        <nav class="nav"><a href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>Tableau de bord</span></a><a href="#"><span class="nav-icon">◈</span><span>Produits</span></a><a href="#"><span class="nav-icon">▱</span><span>Lots</span></a><a href="#"><span class="nav-icon">♙</span><span>Acteurs</span></a><a href="#"><span class="nav-icon">◎</span><span>Traçabilité</span></a><a href="#"><span class="nav-icon">▰</span><span>Transports</span></a><a href="#"><span class="nav-icon">♢</span><span>Certifications</span></a><a href="#"><span class="nav-icon">▤</span><span>Documents</span></a><a class="active" href="{{ route('profile.edit') }}"><span class="nav-icon">♙</span><span>Mon profil</span></a></nav>
        <div class="sustain"><strong>Une alimentation plus sûre et plus durable</strong><br>avec NutriTrace</div>
    </aside>
    <main class="main">
        <header class="top"><input class="search" type="search" placeholder="⌕  Rechercher un produit, un lot, un acteur..."><div class="top-user"><span class="avatar">♙</span><div><strong>{{ $user->fullname }}</strong><br><small>{{ ucfirst($user->getRoleNames()->first() ?? 'Utilisateur') }}</small></div></div></header>
        <section class="content">
            <h1>Mon profil</h1><p class="subtitle">Gérez vos informations personnelles et vos préférences de compte.</p>
            <div class="layout">
                <aside class="card identity"><div class="identity-top"></div><div class="profile-avatar">♙</div><h2>{{ $user->fullname }}</h2><p>{{ ucfirst($user->getRoleNames()->first() ?? 'Utilisateur') }}</p><span class="status">● Compte actif</span></aside>
                <div class="card form-card">
                    <div class="form-head"><div><h2>Informations personnelles</h2><p>Modifiez vos informations de profil.</p></div><span class="edit-label">✎ Modifier</span></div>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf @method('PUT')
                        <div class="fields">
                            <label>Nom complet<input name="fullname" value="{{ old('fullname', $user->fullname) }}" required>@error('fullname')<span class="error">{{ $message }}</span>@enderror</label>
                            <label>Email<input type="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email')<span class="error">{{ $message }}</span>@enderror</label>
                            <label>Téléphone<input name="phone" value="{{ old('phone', $user->phone) }}">@error('phone')<span class="error">{{ $message }}</span>@enderror</label>
                            <label>Date de naissance<input type="date" name="birthdate" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}">@error('birthdate')<span class="error">{{ $message }}</span>@enderror</label>
                            <label>Gouvernorat<input name="governorate" value="{{ old('governorate', $user->governorate) }}" required>@error('governorate')<span class="error">{{ $message }}</span>@enderror</label>
                            <label>Ville<input name="city" value="{{ old('city', $user->city) }}">@error('city')<span class="error">{{ $message }}</span>@enderror</label>
                            <label style="grid-column:1/-1">Adresse<input name="address" value="{{ old('address', $user->address) }}">@error('address')<span class="error">{{ $message }}</span>@enderror</label>
                        </div>
                        <div class="actions">@if(session('status'))<span class="success">{{ session('status') }}</span>@endif<a class="back" href="{{ route('dashboard') }}">Retour</a><button class="button" type="submit">Enregistrer les modifications</button></div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
