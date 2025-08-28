<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUser extends Mailable
{
    use Queueable, SerializesModels;

    public $adminName;
    public $adminEmail;
    public $userData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($adminName, $adminEmail, $userData = null)
    {
        $this->adminName = $adminName;
        $this->adminEmail = $adminEmail;
        $this->userData = $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.verify_user_notification'))
                    ->view('emails.verify-user')
                    ->with([
                        'adminName' => $this->adminName,
                        'userData' => $this->userData,
                    ]);
    }
}
