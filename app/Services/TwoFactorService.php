<?php

namespace App\Services;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TwoFactorService
{
    protected const SESSION_CODE_KEY = '2fa_code';
    protected const SESSION_EXPIRES_KEY = '2fa_expires_at';
    protected const CODE_TTL_MINUTES = 10;

    /**
     * Generate a new code, store it in the session, and email it to the user.
     */
    public function generateAndSend(User $user): string
    {
        $code = (string) random_int(100000, 999999);

        session([
            self::SESSION_CODE_KEY => $code,
            self::SESSION_EXPIRES_KEY => now()->addMinutes(self::CODE_TTL_MINUTES),
        ]);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code));

        return $code;
    }

    /**
     * Check a submitted code against the one stored in session.
     */
    public function verify(string $submittedCode): bool
    {
        $valid = $submittedCode === session(self::SESSION_CODE_KEY)
            && now()->lessThan(session(self::SESSION_EXPIRES_KEY));

        if ($valid) {
            session()->forget([self::SESSION_CODE_KEY, self::SESSION_EXPIRES_KEY]);
            session(['2fa_verified' => true]);
        }

        return $valid;
    }

    /**
     * Mask an email for display, e.g. j***@gmail.com
     */
    public function maskEmail(string $email): string
    {
        return preg_replace('/(?<=.).(?=.*@)/', '*', $email);
    }
}