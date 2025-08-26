<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;

    public function __construct(User $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Your Two-Factor Authentication Code')
                    ->view('emails.two-factor-code')
                    ->with([
                        'user' => $this->user,
                        'code' => $this->code,
                        'expiresAt' => $this->user->two_factor_expires_at,
                    ]);
    }
}
