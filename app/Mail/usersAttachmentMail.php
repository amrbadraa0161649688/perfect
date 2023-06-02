<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class usersAttachmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $employee;
    public $from_email;
    public $subject;
    public $attachment_type;
    public $attachment_link;

    public function __construct($employee, $from_email, $attachment_type, $attachment_link,$subject)
    {
        $this->from_email = $from_email;
        $this->employee = $employee;
        $this->subject = $subject;
        $this->attachment_type = $attachment_type;
        $this->attachment_link = $attachment_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Emails.usersAttachmentMail')
            ->from($this->from_email)
            ->subject($this->subject);
    }
}
