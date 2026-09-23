<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class TraceabilityPolicy
{
    public function view(User $user, Lot $lot): bool
    {
        return $user->can('traceability.view') || $user->hasRole(['producteur', 'transformateur', 'distributeur', 'consommateur']);
    }

    public function create(User $user): bool
    {
        return $user->can('traceability.create') || $user->hasRole(['producteur', 'transformateur', 'distributeur']);
    }

    public function transfer(User $user, Lot $lot): bool
    {
        return $user->can('traceability.transfer') && $lot->status !== 'blocked';
    }

    public function block(User $user, Lot $lot): bool
    {
        return $user->can('traceability.block') && $user->hasRole('producteur');
    }

    public function recall(User $user, Lot $lot): bool
    {
        return $user->can('traceability.recall');
    }
}
