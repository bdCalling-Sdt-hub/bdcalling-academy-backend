<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppNotifications extends Notification
{
    use Queueable;
    private $message;
    private $userId;
    /**
     * Create a new notification instance.
     */
    public function __construct($message,$userId)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'user_id' => $this->userId
        ];
    }
}
