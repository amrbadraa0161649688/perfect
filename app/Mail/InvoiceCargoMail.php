<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceCargoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $waybill_attachments;
    public $details;

    public function __construct($details, $waybill_attachments)
    {

        $this->details = $details;
        $this->waybill_attachments = $waybill_attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('اصدار فاتوره')
            ->view('Emails.invoice-cargo-mail');

        foreach ($this->waybill_attachments as $filePath) {
            $email->attach($filePath);
        }

        return $email;
    }
}
