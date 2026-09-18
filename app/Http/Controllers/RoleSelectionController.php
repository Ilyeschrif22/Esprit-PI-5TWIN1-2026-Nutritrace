<?php

namespace App\Http\Controllers;

use App\Models\RoleDocument;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RoleSelectionController extends Controller
{
    public function create(): View
    {
        return view('role-selection');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:producteur,transformateur,distributeur,consommateur',
            'document' => 'required_if:role,producteur,transformateur,distributeur|file',
        ]);

        $user = Auth::user();

        if ($validated['role'] === 'consommateur') {
            $user->assignRole('consommateur');
            return redirect()->route('dashboard');
        }

        $documentPath = $request->file('document')->store('role-documents', 'public');

        RoleDocument::create([
            'user_id' => $user->id,
            'role' => $validated['role'],
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);

        // Assign role immediately - no manual approval needed for now
        $user->assignRole($validated['role']);

        // Redirect to dashboard instead of pending-approval page
        return redirect()->route('dashboard');
    }
}
