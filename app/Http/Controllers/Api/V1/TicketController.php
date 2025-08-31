<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\TicketReplyMail;
use App\Models\ContactUs;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Http\Requests\Vendor\TicketReplyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketReplyNotification;

class TicketController extends Controller
{
    /**
     * Display user's tickets
     */
    public function index()
    {
        $tickets = ContactUs::with(['replies.user', 'assignedTo'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $tickets,
            'message' => 'Tickets retrieved successfully'
        ]);
    }

    /**
     * Create a new ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'sometimes|in:low,medium,high,urgent'
        ]);

        $ticket = ContactUs::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'status' => ContactUs::STATUS_OPEN,
            'priority' => $request->priority ?? ContactUs::PRIORITY_MEDIUM
        ]);

        return response()->json([
            'success' => true,
            'data' => $ticket->load(['replies.user', 'assignedTo']),
            'message' => 'Ticket created successfully'
        ], 201);
    }

    /**
     * Display specific ticket with replies
     */
    public function show($id)
    {
        $ticket = ContactUs::with(['replies.user', 'assignedTo', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Mark customer replies as read
        $ticket->replies()
            ->where('reply_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'success' => true,
            'data' => $ticket,
            'message' => 'Ticket retrieved successfully'
        ]);
    }

    /**
     * Add reply to ticket
     */
    public function reply(TicketReplyRequest $request, $id)
    {
      

        $ticket = ContactUs::where('user_id', Auth::id())
            ->where('status', '!=', ContactUs::STATUS_CLOSED)
            ->findOrFail($id);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }

        $reply = TicketReply::create([
            'contact_us_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'reply_type' => 'customer',
            'attachments' => $attachments
        ]);

        // Update ticket status if closed
        if ($ticket->status === ContactUs::STATUS_RESOLVED) {
            $ticket->update(['status' => ContactUs::STATUS_OPEN]);
        }

        // Send notification to assigned admin if exists
        if ($ticket->assignedTo) {
            $ticket->assignedTo->notify(new TicketReplyNotification($ticket, $reply));
        }

        return response()->json([
            'success' => true,
            'data' => $reply->load('user'),
            'message' => 'Reply added successfully'
        ], 201);
    }

    /**
     * Close ticket
     */
    public function close($id)
    {
        $ticket = ContactUs::where('user_id', Auth::id())
            ->findOrFail($id);

        $ticket->close();

        return response()->json([
            'success' => true,
            'message' => 'Ticket closed successfully'
        ]);
    }

    /**
     * Reopen ticket
     */
    public function reopen($id)
    {
        $ticket = ContactUs::where('user_id', Auth::id())
            ->findOrFail($id);

        $ticket->reopen();

        return response()->json([
            'success' => true,
            'message' => 'Ticket reopened successfully'
        ]);
    }
}
