<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TicketReplyMail;
use App\Models\ContactUs;
use App\Models\TicketReply;
use App\Models\User;
use App\Notifications\TicketReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    /**
     * Display tickets listing
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tickets = ContactUs::with(['user', 'assignedTo', 'replies'])
                ->select('contact_us.*');

            return DataTables::of($tickets)
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('assigned_to_name', function ($row) {
                    return $row->assignedTo->name ?? 'Unassigned';
                })
                ->addColumn('replies_count', function ($row) {
                    return $row->replies->count();
                })
                ->addColumn('unread_count', function ($row) {
                    return $row->replies()->where('reply_type', 'customer')->unread()->count();
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'open' => '<span class="badge bg-success">'.__('tickets.statuses.open').'</span>',
                        'in_progress' => '<span class="badge bg-warning">'.__('tickets.statuses.in_progress').'</span>',
                        'resolved' => '<span class="badge bg-info">'.__('tickets.statuses.resolved').'</span>',
                        'closed' => '<span class="badge bg-secondary">'.__('tickets.statuses.closed').'</span>',
                    ];
                    return $badges[$row->status] ?? '<span class="badge bg-light">'.__('tickets.statuses.unknown').'</span>';
                })
                ->addColumn('priority_badge', function ($row) {
                    $badges = [
                        'low' => '<span class="badge bg-light text-dark">'.__('tickets.low').'</span>',
                        'medium' => '<span class="badge bg-primary">'.__('tickets.medium').'</span>',
                        'high' => '<span class="badge bg-warning">'.__('tickets.high').'</span>',
                        'urgent' => '<span class="badge bg-danger">'.__('tickets.urgent').'</span>',
                    ];
                    return $badges[$row->priority] ?? '<span class="badge bg-light">'.__('tickets.medium').'</span>';
                })
                ->addColumn('actions', function ($row) {
                    $viewUrl = route('admin.tickets.show', $row->id);
                    $assignUrl = route('admin.tickets.assign', $row->id);
                    
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . $viewUrl . '">
                                    <i data-feather="eye"></i> ' . __('tickets.view') . '
                                </a>
                                <a class="dropdown-item assign-ticket" href="#" data-url="' . $assignUrl . '">
                                    <i data-feather="user"></i> ' . __('tickets.assign') . '
                                </a>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['status_badge', 'priority_badge', 'actions'])
                ->make(true);
        }

        $admins = User::where('type', User::TYPE_ADMIN)->get();
        return view('admin.pages.tickets.index', compact('admins'));
    }

    /**
     * Display specific ticket with replies
     */
    public function show($id)
    {
        $ticket = ContactUs::with(['user', 'assignedTo', 'replies.user'])
            ->findOrFail($id);

        // Mark admin replies as read
        $ticket->replies()
            ->where('reply_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $admins = User::where('type', User::TYPE_ADMIN)->get();
        
        return view('admin.pages.tickets.show', compact('ticket', 'admins'));
    }

    /**
     * Add reply to ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $ticket = ContactUs::findOrFail($id);

        $reply = TicketReply::create([
            'contact_us_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'reply_type' => 'admin'
        ]);

        // Send email and notification to customer
        try {
            Mail::to($ticket->user->email)->send(new TicketReplyMail($ticket, $reply));
            $ticket->user->notify(new TicketReplyNotification($ticket, $reply));
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket reply notification: ' . $e->getMessage());
        }

       flash()->success('Reply sent successfully');
       return redirect()->back();
    }

    /**
     * Assign ticket to admin
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $ticket = ContactUs::findOrFail($id);
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => $ticket->status === ContactUs::STATUS_OPEN ? ContactUs::STATUS_IN_PROGRESS : $ticket->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket assigned successfully'
        ]);
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'notes' => 'nullable|string'
        ]);

        $ticket = ContactUs::findOrFail($id);
        
        if ($request->status === ContactUs::STATUS_CLOSED) {
            $ticket->close($request->notes);
        } else {
            $ticket->update([
                'status' => $request->status,
                'notes' => $request->notes
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully'
        ]);
    }

    /**
     * Update ticket priority
     */
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket = ContactUs::findOrFail($id);
        $ticket->update(['priority' => $request->priority]);

        return response()->json([
            'success' => true,
            'message' => 'Ticket priority updated successfully'
        ]);
    }
}
