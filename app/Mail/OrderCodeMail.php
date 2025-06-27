<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCodeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order; // public so it’s accessible in the view
    public $code; // public so it’s accessible in the view

    public function __construct($order,$code)
    {
        $this->order = $order;
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Your Order Details')
                    ->view('emails.order-code');
    }
}
