<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransferNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details; // this will hold data we send to the email

    /**
     * Create a new message instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->details['subject'])
                    ->view('emails.transfer_notification');
    }
}
