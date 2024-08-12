<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $time;
    protected $name;
    protected $user;

    public function __construct($message,$time,$name,$user)
    {
        //
        $this->message = $message;
        $this->time = $time;
        $this->name = $name;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'time' => $this->time,
            'name'=>$this->name,
            'user' => $this->user,
        ];
    }
}
