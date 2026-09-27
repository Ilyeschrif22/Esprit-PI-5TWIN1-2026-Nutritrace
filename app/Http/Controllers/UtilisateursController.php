<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UtilisateursController extends Controller
{
    public function index(Request $request): View
    {
        return $this->listing($request);
    }

    public function create(Request $request): View
    {
        return $this->listing($request, 'create', new User());
    }

    public function show(Request $request, User $utilisateur): View
    {
        return $this->listing($request, 'show', $utilisateur->load('roles'));
    }

    public function edit(Request $request, User $utilisateur): View
    {
        return $this->listing($request, 'edit', $utilisateur->load('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'cin' => ['nullable', 'digits:8', 'unique:users,cin'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::exists('roles', 'name')],
            'is_active' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $utilisateur = User::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'cin' => $validated['cin'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'is_active' => $validated['is_active'],
        ]);

        $utilisateur->forceFill([
            'email_verified_at' => $utilisateur->is_active ? now() : null,
        ])->save();
        $utilisateur->assignRole($validated['role']);

        return redirect()->route('utilisateurs')->with('status', 'Utilisateur ajouté avec succès.');
    }

    public function update(Request $request, User $utilisateur): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($utilisateur->id)],
            'cin' => ['nullable', 'digits:8', Rule::unique('users', 'cin')->ignore($utilisateur->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::exists('roles', 'name')],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($utilisateur->is($request->user()) && ! (bool) $validated['is_active']) {
            return back()->withErrors(['is_active' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $utilisateur->update([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'cin' => $validated['cin'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'],
            ...(! empty($validated['password']) ? ['password' => $validated['password']] : []),
        ]);

        $utilisateur->syncRoles([$validated['role']]);

        return redirect()->route('utilisateurs')->with('status', 'Utilisateur mis à jour.');
    }

    public function toggleStatus(Request $request, User $utilisateur): RedirectResponse
    {
        if ($utilisateur->is($request->user())) {
            return back()->withErrors(['status' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $isActive = ! $utilisateur->is_active;
        $utilisateur->update(['is_active' => $isActive]);

        return redirect()->route('utilisateurs')->with(
            'status',
            $isActive ? 'Compte activé.' : 'Compte désactivé.'
        );
    }

    public function destroy(Request $request, User $utilisateur): RedirectResponse
    {
        if ($utilisateur->is($request->user())) {
            return back()->withErrors(['delete' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        $utilisateur->delete();

        return redirect()->route('utilisateurs')->with('status', 'Utilisateur supprimé.');
    }

    private function listing(Request $request, ?string $modal = null, ?User $selectedUser = null): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'role' => ['nullable', Rule::exists('roles', 'name')],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $query = User::query()->with('roles')->orderBy('fullname');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($query) use ($search) {
                $query->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['role'])) {
            $query->whereHas('roles', fn ($query) => $query->where('name', $filters['role']));
        }

        if (($filters['status'] ?? null) === 'active') {
            $query->where('is_active', true);
        } elseif (($filters['status'] ?? null) === 'inactive') {
            $query->where('is_active', false);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return view('pages.utilisateurs', [
            'user' => Auth::user()->loadMissing('roles'),
            'users' => $query->paginate(10)->withQueryString(),
            'roles' => Role::query()->orderBy('name')->pluck('name'),
            'totalUsers' => User::count(),
            'activeUsers' => User::where('is_active', true)->count(),
            'inactiveUsers' => User::where('is_active', false)->count(),
            'adminUsers' => User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->count(),
            'partnerUsers' => User::whereHas('roles', fn ($query) => $query->whereIn('name', [
                'producteur', 'transformateur', 'distributeur', 'consommateur',
            ]))->count(),
            'filters' => $filters,
            'modal' => $modal,
            'selectedUser' => $selectedUser,
        ]);
    }
}
