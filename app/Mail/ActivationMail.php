<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $userName;
    public $activationCode;

    public function __construct($userName, $activationCode)
    {
        $this->userName = $userName;
        $this->activationCode = $activationCode;
    }

    public function build()
    {
        return $this->subject(__('admin.activation_mail_subject'))
                    ->view('emails.activation');
    }
}
