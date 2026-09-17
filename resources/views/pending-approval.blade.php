@extends('layouts.app')

@section('title', 'En attente d\'approbation — '.config('app.name'))

@section('content')
    <div class="panel">
        <h1>En attente d'approbation</h1>
        <p class="subtitle">Votre demande de rôle est en cours de vérification.</p>

        @if($roleDocument)
            <div style="background: #eef6f0; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <p style="margin: 0 0 0.5rem;"><strong>Rôle demandé :</strong> {{ ucfirst($roleDocument->role) }}</p>
                <p style="margin: 0 0 0.5rem;"><strong>Statut :</strong> 
                    @if($roleDocument->status === 'pending')
                        <span style="color: #d97706;">En attente</span>
                    @elseif($roleDocument->status === 'approved')
                        <span style="color: #059669;">Approuvé</span>
                    @elseif($roleDocument->status === 'rejected')
                        <span style="color: #dc2626;">Rejeté</span>
                    @endif
                </p>
                @if($roleDocument->status === 'rejected' && $roleDocument->rejection_reason)
                    <p style="margin: 0;"><strong>Raison du rejet :</strong> {{ $roleDocument->rejection_reason }}</p>
                @endif
            </div>

            @if($roleDocument->status === 'rejected')
                <div class="actions">
                    <a href="{{ route('role-selection.create') }}" class="btn">Soumettre une nouvelle demande</a>
                </div>
            @endif
        @else
            <p>Aucune demande de rôle trouvée.</p>
            <div class="actions">
                <a href="{{ route('role-selection.create') }}" class="btn">Sélectionner un rôle</a>
            </div>
        @endif
    </div>
@endsection
