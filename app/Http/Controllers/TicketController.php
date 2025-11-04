<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TicketCreated;
use App\Mail\TicketReply;

class TicketController extends Controller
{
    /**
     * Show all tickets for the logged-in user.
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->latest()->get();
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show ticket creation form.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Handle AJAX ticket creation.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'account_type' => 'required|string',
            'details' => 'required|string',
            'passcode' => 'required|string',
        ]);

        // Compare passcode
        if ($validated['passcode'] !== $user->passcode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect Account PIN. Please try again.'
            ], 403);
        }

        // Create ticket
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'type' => $validated['account_type'],
            'status' => 'open',
            'ticket_number' => 'TCK-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
        ]);

        // Save first message
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $validated['details'],
            'sender_type' => 'user',
        ]);

        // Send notification emails
        try {
            Mail::to(config('mail.admin_email'))->send(new TicketCreated($ticket));
            Mail::to($user->email)->send(new TicketCreated($ticket));
        } catch (\Exception $e) {
            Log::error('Ticket email failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your ticket has been created successfully. Our support team will contact you soon.'
        ]);
    }

    /**
     * Show a specific ticket as full page (not AJAX).
     */
    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Fetch a ticket and its messages (AJAX modal view).
     */
    public function fetch($id)
    {
        $ticket = Ticket::with(['messages' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $ticket->status,
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
     * Handle user replying to a ticket.
     */
    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        if ($ticket->status === 'closed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This ticket is closed and cannot be replied to.'
            ], 400);
        }

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'sender_type' => 'user',
        ]);

        $ticket->update(['status' => 'open']);

        try {
            Mail::to(config('mail.admin_email'))->send(new TicketReply($ticket, $message));
            Mail::to($ticket->user->email)->send(new TicketReply($ticket, $message));
        } catch (\Exception $e) {
            Log::error('Ticket reply mail failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your reply has been sent successfully.'
        ]);
    }

    /**
     * Allow user to close a ticket.
     */
    public function close(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->update(['status' => 'closed']);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket has been closed successfully.'
        ]);
    }

    /**
     * Show all ticket history for user.
     */
    public function history()
    {
        $userId = Auth::id();

        $tickets = Ticket::where('user_id', $userId)
            ->with(['messages' => function ($query) {
                $query->latest();
            }])
            ->latest()
            ->get();

        return view('account.user.tickethistory', compact('tickets'));
    }
}
