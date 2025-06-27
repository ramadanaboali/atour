<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDetailsMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order_id; // public so it’s accessible in the view
    public $order; // public so it’s accessible in the view

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
        $this->order = Order::findOrFail($order_id);
    }

    public function build()
    {
        return $this->subject('Your Order Details')
                    ->view('emails.order-details');
    }
}
