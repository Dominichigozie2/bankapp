<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $currentAccountNumber;

    public function __construct($name, $currentAccountNumber)
    {
        $this->name = $name;
        $this->currentAccountNumber = $currentAccountNumber;
    }

    public function build()
    {
        return $this->subject('Your Account Account Number')
                    ->markdown('emails.registration');
    }
}
