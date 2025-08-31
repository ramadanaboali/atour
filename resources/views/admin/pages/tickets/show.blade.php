@extends('admin.layouts.master')
@section('title')
<title>{{ config('app.name') }} | {{ __('tickets.ticket_number', ['number' => $ticket->id]) }}</title>
@endsection
@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-8 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h1 class="bold mb-0 mt-1 text-dark">
                    <i data-feather="help-circle" class="font-medium-2"></i>
                    <span>{{ __('tickets.ticket_number', ['number' => $ticket->id]) }} - {{ $ticket->title }}</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="content-header-right text-md-end col-md-4 col-12 d-md-block">
        <div class="mb-1 breadcrumb-right">
            <a class="btn btn-sm btn-outline-secondary waves-effect" href="{{ route('admin.tickets.index') }}">
                <i data-feather="arrow-left"></i>
                <span>{{ __('tickets.back_to_tickets') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="content-body">
    <div class="row">
        <!-- Ticket Details -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('tickets.ticket_details') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>{{ __('tickets.customer') }}:</strong><br>
                        {{ $ticket->user->name }}<br>
                        <small class="text-muted">{{ $ticket->user->email }}</small>
                    </div>
                    
                    <div class="mb-2">
                        <strong>{{ __('tickets.status') }}:</strong><br>
                        <select class="form-select form-select-sm" id="ticketStatus" data-ticket-id="{{ $ticket->id }}">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>{{ __('tickets.open') }}</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>{{ __('tickets.in_progress') }}</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>{{ __('tickets.resolved') }}</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>{{ __('tickets.closed') }}</option>
                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <strong>{{ __('tickets.priority') }}:</strong><br>
                        <select class="form-select form-select-sm" id="ticketPriority" data-ticket-id="{{ $ticket->id }}">
                            <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>{{ __('tickets.low') }}</option>
                            <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>{{ __('tickets.medium') }}</option>
                            <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>{{ __('tickets.high') }}</option>
                            <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>{{ __('tickets.urgent') }}</option>
                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <strong>{{ __('tickets.assigned_to') }}:</strong><br>
                        <select class="form-select form-select-sm" id="ticketAssigned" data-ticket-id="{{ $ticket->id }}">
                            <option value="">{{ __('tickets.unassigned') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <strong>{{ __('tickets.created_at') }}:</strong><br>
                        <small class="text-muted">{{ $ticket->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    
                    @if($ticket->closed_at)
                    <div class="mb-2">
                        <strong>{{ __('tickets.closed_at') }}:</strong><br>
                        <small class="text-muted">{{ $ticket->closed_at->format('M d, Y H:i') }}</small>
                    </div>
                    @endif
                    
                    @if($ticket->notes)
                    <div class="mb-2">
                        <strong>{{ __('tickets.notes') }}:</strong><br>
                        <small class="text-muted">{{ $ticket->notes }}</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Conversation -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('tickets.conversation') }}</h4>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;" id="conversationArea">
                    <!-- Original Ticket -->
                    <div class="d-flex mb-3">
                        <div class="">
                            <img src="{{ $ticket->user->photo ?? asset('app-assets/images/portrait/small/avatar-s-1.jpg') }}" 
                                 alt="{{ __('tickets.avatar') }}" height="40" width="40" class="rounded-circle">
                        </div>
                        <div class="flex-grow-1">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $ticket->user->name }}</strong>
                                    <small class="text-muted">{{ $ticket->created_at->format('M d, Y H:i') }}</small>
                                </div>
                                <p class="mb-0">{!! nl2br(e($ticket->description)) !!}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Replies -->
                    @foreach($ticket->replies as $reply)
                    <div class="d-flex mb-3 {{ $reply->reply_type === 'admin' ? 'justify-content-end' : '' }}">
                        @if($reply->reply_type === 'customer')
                        <div class="">
                            <img src="{{ $reply->user->photo ?? asset('app-assets/images/portrait/small/avatar-s-1.jpg') }}" 
                                 alt="Avatar" height="40" width="40" class="rounded-circle">
                        </div>
                        @endif
                        
                        <div class="flex-grow-1" style="max-width: 80%;">
                            <div class="p-3 rounded {{ $reply->reply_type === 'admin' ? 'bg-primary text-white' : 'bg-light' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $reply->user->name }}</strong>
                                    <small class="{{ $reply->reply_type === 'admin' ? 'text-white-50' : 'text-muted' }}">
                                        {{ $reply->created_at->format('M d, Y H:i') }}
                                    </small>
                                </div>
                                <p class="mb-0">{!! nl2br(e($reply->message)) !!}</p>
                                
                                @if($reply->attachments && count($reply->attachments) > 0)
                                <div class="mt-2">
                                    <small><strong>{{ __('tickets.attachments') }}:</strong></small>
                                    @foreach($reply->attachments as $attachment)
                                        <div class="mt-1">
                                            <i data-feather="paperclip" class="font-small-2"></i>
                                            <small>{{ $attachment['name'] }} ({{ number_format($attachment['size'] / 1024, 2) }} {{ __('tickets.kb') }})</small>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($reply->reply_type === 'admin')
                        <div class="">
                            <img src="{{ $reply->user->photo ?? asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}" 
                                 alt="Avatar" height="40" width="40" class="rounded-circle">
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                @if($ticket->status !== 'closed')
                <div class="card-footer">
                    <form id="replyForm" action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="message" id="replyMessage" class="form-control" rows="4" 
                                      placeholder="Type your reply..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" id="sendReply" class="btn btn-primary">
                                <i data-feather="send"></i> {{ __('tickets.actions.send_reply') }}
                            </button>
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addNote()">
                                    <i data-feather="edit-3"></i> {{ __('tickets.actions.add_note') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @else
                <div class="card-footer bg-light">
                    <p class="text-center text-muted mb-0">
                        <i data-feather="lock"></i> {{ __('tickets.status.closed') }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('tickets.actions.add_note') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <div class="mb-3">
                        <label for="noteText" class="form-label">{{ __('tickets.actions.note') }}</label>
                        <textarea class="form-control" id="noteText" rows="3" placeholder="{{ __('tickets.placeholders.add_internal_note') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('tickets.actions.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveNote()">{{ __('tickets.actions.save') }}</button>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
    // Reply form submission
    
    
    // Status change
    $('#ticketStatus').on('change', function() {
        var ticketId = $(this).data('ticket-id');
        var status = $(this).val();
        
        $.ajax({
            url: "{{ route('admin.tickets.status', ':id') }}".replace(':id', ticketId),
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    if (status === 'closed') {
                        location.reload();
                    }
                }
            },
            error: function(xhr) {
                toastr.error('{{ __('tickets.messages.failed_to_update_status') }}');
            }
        });
    });
    
    // Priority change
    $('#ticketPriority').on('change', function() {
        var ticketId = $(this).data('ticket-id');
        var priority = $(this).val();
        
        $.ajax({
            url: "{{ route('admin.tickets.priority', ':id') }}".replace(':id', ticketId),
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                priority: priority
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('{{ __('tickets.messages.failed_to_update_priority') }}');
            }
        });
    });
    
    // Assignment change
    $('#ticketAssigned').on('change', function() {
        var ticketId = $(this).data('ticket-id');
        var assignedTo = $(this).val();
        
        $.ajax({
            url: "{{ route('admin.tickets.assign', ':id') }}".replace(':id', ticketId),
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                assigned_to: assignedTo
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('{{ __('tickets.messages.failed_to_assign_ticket') }}');
            }
        });
    });
    
    // Auto-scroll to bottom of conversation
    $('#conversationArea').scrollTop($('#conversationArea')[0].scrollHeight);
    
    function addNote() {
        $('#addNoteModal').modal('show');
    }
    
    function saveNote() {
        var note = $('#noteText').val();
        if (!note.trim()) {
            toastr.error('{{ __('tickets.messages.please_enter_a_note') }}');
            return;
        }
        
        // Add note logic here
        $('#addNoteModal').modal('hide');
        $('#noteText').val('');
        toastr.success('{{ __('tickets.messages.note_added_successfully') }}');
    }
</script>
@endpush
