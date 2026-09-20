<?php

namespace App\Http\Controllers;

use App\Models\RoleDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PendingApprovalController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $roleDocument = RoleDocument::where('user_id', $user->id)->latest()->first();

        return view('pending-approval', [
            'roleDocument' => $roleDocument,
        ]);
    }
}
