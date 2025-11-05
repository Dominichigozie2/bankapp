<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $loan;
    public $subjectLine;
    public $messageBody;

    public function __construct($user, $loan, $subjectLine, $messageBody)
    {
        $this->user = $user;
        $this->loan = $loan;
        $this->subjectLine = $subjectLine;
        $this->messageBody = $messageBody;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->markdown('emails.loan_status')
                    ->with([
                        'user' => $this->user,
                        'loan' => $this->loan,
                        'messageBody' => $this->messageBody,
                    ]);
    }
}
