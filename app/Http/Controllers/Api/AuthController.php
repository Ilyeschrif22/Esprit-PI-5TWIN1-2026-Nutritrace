<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle API login request and return JWT token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::validate($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Email not verified. Please complete the verification process.',
            ], 403);
        }

        // Generate JWT token
        $token = $this->jwtService->generateToken($user);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => env('JWT_EXPIRATION', 1440) * 60, // in seconds
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ],
        ], 200);
    }

    /**
     * Get the authenticated user information.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ],
        ]);
    }

    /**
     * Handle API logout request.
     */
    public function logout(Request $request)
    {
        // For JWT, the client should simply discard the token
        // Server-side JWT invalidation would require a blacklist or database storage
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
