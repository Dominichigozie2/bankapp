<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationPasscodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $passcode;

    public function __construct($name, $passcode)
    {
        $this->name = $name;
        $this->passcode = $passcode;
    }

    public function build()
    {
        return $this->subject('Your Account Passcode')
                    ->markdown('emails.registration_passcode');
    }
}
