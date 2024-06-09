<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class FollowUpMessage extends Notification
{
    use Queueable;

    public $phone_number;
    public $message;
    public function __construct($phone_number,$message)
    {
        $this->phone_number = $phone_number;
        $this->message = $message;
    }


    public function via(object $notifiable): array
    {
        return ['vonage'];
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->to($this->phone_number)
            ->clientReference((string) $notifiable->id)
            ->content($this->message);
    }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
