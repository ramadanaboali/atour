<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Reply - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .ticket-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .reply-content {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-open { background-color: #d4edda; color: #155724; }
        .status-in-progress { background-color: #fff3cd; color: #856404; }
        .status-resolved { background-color: #d1ecf1; color: #0c5460; }
        .status-closed { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }} - Support Ticket Reply</h1>
        <p>You have received a new reply to your support ticket.</p>
    </div>

    <div class="ticket-info">
        <h3>Ticket Information</h3>
        <p><strong>Ticket ID:</strong> #{{ $ticket->id }}</p>
        <p><strong>Title:</strong> {{ $ticket->title }}</p>
        <p><strong>Status:</strong> 
            <span class="status-badge status-{{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
        </p>
        <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
        <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
    </div>

    <div class="reply-content">
        <h3>New Reply from {{ $reply->user->name }}</h3>
        <p><strong>Date:</strong> {{ $reply->created_at->format('M d, Y H:i') }}</p>
        <div style="margin-top: 15px; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #007bff;">
            {!! nl2br(e($reply->message)) !!}
        </div>
        
        @if($reply->attachments && count($reply->attachments) > 0)
            <div style="margin-top: 15px;">
                <strong>Attachments:</strong>
                <ul>
                    @foreach($reply->attachments as $attachment)
                        <li>{{ $attachment['name'] }} ({{ number_format($attachment['size'] / 1024, 2) }} KB)</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/tickets/' . $ticket->id) }}" class="btn">View Full Conversation</a>
    </div>

    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }} support system.</p>
        <p>If you have any questions, please don't hesitate to reply to this ticket.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
