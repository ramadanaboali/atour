<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $contactData;

    /**
     * Create a new message instance.
     */
    public function __construct( $contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Atour ContactUs Email')
            ->view('emails.contact_us')
            ->with(['contactData' => $this->contactData]);
    }
}