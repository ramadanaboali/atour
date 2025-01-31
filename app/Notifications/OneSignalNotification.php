<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OneSignalNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    // Store notification in the database
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'time' => now()->toDateTimeString(),
        ];
    }
}
