<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Card;

class CardRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $card;

    public function __construct(User $user, Card $card)
    {
        $this->user = $user;
        $this->card = $card;
    }

    public function build()
    {
        return $this->subject('Your Card Request Was Submitted Successfully')
                    ->markdown('emails.card.request');
    }
}
