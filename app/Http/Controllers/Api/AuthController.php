<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            'user' => $this->userPayload($user),
        ], 200);
    }

    /**
     * Get the authenticated user information (profile).
     */
    public function me(Request $request)
    {
        $user = $request->user()->load('roles');

        return response()->json([
            'user' => $this->userPayload($user, true),
        ]);
    }

    /**
     * Handle API logout request.
     */
    public function logout(Request $request)
    {
        // For JWT, the client should simply discard the token
        // Server-side JWT invalidation would require a blacklist or database storage.
        // The authenticated user data is returned so the caller can confirm
        // which account was logged out.
        $user = $request->user()->load('roles');

        return response()->json([
            'message' => 'Successfully logged out',
            'user' => $this->userPayload($user),
        ]);
    }

    /**
     * Build the public user payload returned by the API.
     */
    protected function userPayload(User $user, bool $withTimestamps = false): array
    {
        $payload = [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
            'cin' => $user->cin,
            'phone' => $user->phone,
            'birthdate' => $user->birthdate?->toDateString(),
            'governorate' => $user->governorate,
            'city' => $user->city,
            'address' => $user->address,
        ];

        if ($withTimestamps) {
            $payload['email_verified_at'] = $user->email_verified_at?->toDateTimeString();
            $payload['created_at'] = $user->created_at?->toDateTimeString();
        }

        return $payload;
    }
}
