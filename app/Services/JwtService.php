<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Exception;

class JwtService
{
    protected string $secret;
    protected string $algorithm;
    protected int $expiration;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET', env('APP_KEY', 'your-secret-key'));
        $this->algorithm = env('JWT_ALGORITHM', 'HS256');
        $this->expiration = env('JWT_EXPIRATION', 60 * 24); // 24 hours in minutes
    }

    /**
     * Generate a JWT token for a user.
     */
    public function generateToken(User $user): string
    {
        $payload = [
            'iss' => env('APP_URL'),
            'iat' => now()->timestamp,
            'exp' => now()->addMinutes($this->expiration)->timestamp,
            'sub' => $user->id,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    /**
     * Validate and decode a JWT token.
     */
    public function validateToken(string $token): ?object
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return $decoded;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get the user ID from a token.
     */
    public function getUserIdFromToken(string $token): ?int
    {
        $decoded = $this->validateToken($token);
        return $decoded ? $decoded->sub : null;
    }

    /**
     * Get the user from a token.
     */
    public function getUserFromToken(string $token): ?User
    {
        $userId = $this->getUserIdFromToken($token);
        return $userId ? User::find($userId) : null;
    }

    /**
     * Get the roles from a token.
     */
    public function getRolesFromToken(string $token): ?array
    {
        $decoded = $this->validateToken($token);
        return $decoded ? (array) $decoded->roles : null;
    }

    /**
     * Get the permissions from a token.
     */
    public function getPermissionsFromToken(string $token): ?array
    {
        $decoded = $this->validateToken($token);
        return $decoded ? (array) $decoded->permissions : null;
    }
}
