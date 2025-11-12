<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Deposit;
use App\Models\User;

class DepositNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $request;

    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('Deposit Preview Submitted')
                    ->view('emails.deposit_notification'); // your existing blade
    }
}
