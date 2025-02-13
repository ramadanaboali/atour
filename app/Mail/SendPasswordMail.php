<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $password;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($email,$password)
    {
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Atour Welcome Email')
                    ->view('emails.send_password')
                    ->with(['password' => $this->password,'email'=>$this->email]);
    }
}
