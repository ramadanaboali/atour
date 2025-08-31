<?php

namespace App\Notifications;

use App\Models\ContactUs;
use App\Models\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;
    public $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ContactUs $ticket, TicketReply $reply)
    {
        $this->ticket = $ticket;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $actionUrl = url('/admin/tickets/' . $this->ticket->id);
        
        return (new MailMessage)
            ->subject('New Reply to Support Ticket #' . $this->ticket->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new reply has been added to support ticket #' . $this->ticket->id)
            ->line('**Ticket Title:** ' . $this->ticket->title)
            ->line('**From:** ' . $this->reply->user->name)
            ->line('**Message:** ' . substr($this->reply->message, 0, 200) . (strlen($this->reply->message) > 200 ? '...' : ''))
            ->action('View Ticket', $actionUrl)
            ->line('Thank you for using our support system!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'reply_id' => $this->reply->id,
            'reply_message' => substr($this->reply->message, 0, 200),
            'reply_user' => $this->reply->user->name,
            'reply_type' => $this->reply->reply_type,
            'created_at' => $this->reply->created_at->toDateTimeString()
        ];
    }
}
