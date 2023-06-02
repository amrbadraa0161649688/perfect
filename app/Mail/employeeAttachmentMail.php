<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class employeeAttachmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subject;
    public $employee;
    public $from_email;
    public $attachment_type;
    public $attachment_link;

    public function __construct($from_email, $employee, $attachment_type, $attachment_link, $subject)
    {
        $this->subject = $subject;
        $this->employee = $employee;
        $this->from_email = $from_email;
        $this->attachment_type = $attachment_type;
        $this->attachment_link = $attachment_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public
    function build()
    {
        return $this->view('Emails.employeeAttachmentMail')
            ->from($this->from_email)
            ->subject($this->subject);

    }
}
