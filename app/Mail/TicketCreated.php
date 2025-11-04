<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class TicketCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->to($this->ticket->user->email) // ✅ sends to ticket owner's email
                    ->subject("New Ticket Created: {$this->ticket->ticket_number}")
                    ->markdown('emails.tickets.created')
                    ->with([
                        'user' => $this->ticket->user,
                        'ticket' => $this->ticket,
                    ]);
    }
}
