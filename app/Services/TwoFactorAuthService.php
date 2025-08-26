<?php

namespace App\Services;

use App\Models\User;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TwoFactorAuthService
{
    public function sendTwoFactorCode(User $user): bool
    {
        try {
            $code = $user->generateTwoFactorCode();
            
            Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code));
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send 2FA code: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyCode(User $user, string $code): bool
    {
        return $user->verifyTwoFactorCode($code);
    }

    public function enableTwoFactor(User $user): void
    {
        $user->update(['two_factor_enabled' => true]);
    }

    public function disableTwoFactor(User $user): void
    {
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'two_factor_verified_at' => null,
        ]);
    }

    public function requiresTwoFactor(User $user): bool
    {
        return $user->two_factor_enabled;
    }

    public function isCodeExpired(User $user): bool
    {
        return !$user->two_factor_expires_at || $user->two_factor_expires_at->isPast();
    }
}
