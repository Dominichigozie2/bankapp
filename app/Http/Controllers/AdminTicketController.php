<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\Activity; // ✅ Make sure this is imported
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TicketReply;

class AdminTicketController extends Controller
{
    /**
     * Display all tickets for admin.
     */
    public function index()
    {
        $tickets = Ticket::with('user')->latest()->get();
        return view('account.admin.ticket', compact('tickets'));
    }

    /**
     * Fetch specific ticket and its messages (for AJAX modal view).
     */
    public function fetch($id)
    {
        $ticket = Ticket::with(['user', 'messages' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        return response()->json([
            'ticket' => [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'type' => $ticket->type,
                'status' => $ticket->status,
                'user_name' => $ticket->user->first_name . ' ' . $ticket->user->last_name,
                'user_email' => $ticket->user->email,
            ],
            'messages' => $ticket->messages->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'is_admin' => $msg->sender_type === 'admin',
                    'created_at' => $msg->created_at->format('d M Y, h:i A'),
                ];
            }),
        ]);
        
    }

    /**
     * Handle admin replying to a ticket.
     */
    public function reply(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);

    $validated = $request->validate([
        'message' => 'required|string',
    ]);

    if ($ticket->status === 'closed') {
        return response()->json([
            'status' => 'error',
            'message' => 'This ticket is closed and cannot be replied to.'
        ], 400);
    }

    // Save admin reply
    $message = TicketMessage::create([
        'ticket_id' => $ticket->id,
        'user_id' => $ticket->user_id,
        'message' => $validated['message'],
        'sender_type' => 'admin',
    ]);

    // Keep ticket open
    $ticket->update(['status' => 'open']);

    // Log activity for the user
    Activity::create([
        'user_id' => $ticket->user_id,
        'description' => "Admin replied to ticket #{$ticket->ticket_number}: {$validated['message']}",
        'type' => 'ticket',
    ]);

    // Send mail to user
    try {
        Mail::to($ticket->user->email)->send(new TicketReply($ticket, $message));
    } catch (\Exception $e) {
        Log::error('Admin reply mail to user failed: ' . $e->getMessage());
    }

    // Send mail to admin (site_email)
    try {
        $siteEmail = \App\Models\AdminSetting::first()?->site_email;
        if ($siteEmail) {
            Mail::to($siteEmail)->send(new \App\Mail\TicketReply($ticket, $message));
        }
    } catch (\Exception $e) {
        Log::error('Admin reply mail to site admin failed: ' . $e->getMessage());
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Reply sent successfully to user and admin, and logged in activity.'
    ]);
}


    /**
     * Close ticket.
     */
    public function close($id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update(['status' => 'closed']);

        // Log activity
        Activity::create([
            'user_id' => $ticket->user_id,
            'description' => "Admin closed ticket #{$ticket->ticket_number}",
            'type' => 'ticket',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket closed successfully and activity logged.'
        ]);
    }
}
