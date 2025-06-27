<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDetailsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order; // public so it’s accessible in the view

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Your Order Details')
                    ->view('emails.order-details');
    }
}
