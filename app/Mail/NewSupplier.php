<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSupplier extends Mailable
{
    use Queueable, SerializesModels;

    public $adminName;
    public $adminEmail;
    public $supplierData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($adminName, $adminEmail, $supplierData = null)
    {
        $this->adminName = $adminName;
        $this->adminEmail = $adminEmail;
        $this->supplierData = $supplierData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.new_supplier_notification'))
                    ->view('emails.new-supplier')
                    ->with([
                        'adminName' => $this->adminName,
                        'supplierData' => $this->supplierData,
                    ]);
    }
}
