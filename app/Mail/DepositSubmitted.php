<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Deposit;
use App\Models\User;

class DepositSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $user;

    public function __construct(Deposit $deposit, User $user)
    {
        $this->deposit = $deposit;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('New Deposit Submitted')
                    ->view('emails.deposit_submitted');
    }
}
