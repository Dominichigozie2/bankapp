<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use App\Models\TicketMessage;

class TicketReply extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $messageModel;

    public function __construct(Ticket $ticket, TicketMessage $message)
    {
        $this->ticket = $ticket;
        $this->messageModel = $message;
    }

    public function build()
    {
        $subject = "{$this->messageModel->sender_type} replied to {$this->ticket->ticket_number}";
        return $this->subject($subject)
                    ->markdown('emails.tickets.reply');
    }
}
