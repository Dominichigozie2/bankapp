<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

public function build()
{
    return $this->subject('Welcome to Your New Bank')
                ->view('emails.registration')
                ->with(['user' => $this->user]);
}

}
