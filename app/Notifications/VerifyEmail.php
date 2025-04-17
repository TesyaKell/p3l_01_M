<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
    use Queueable;
    private $details;
    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->details['name'] . '!')
            ->line('Account Verification')
            ->line('Thank you for registering!')
            ->line('Here are your account details:')
            ->line('Email : ' . $this->details['email'])
            ->line('Register Date : ' . $this->details['datetime'] . ' WIB')
            ->line('Open this link to verify your email')
            ->action('Verify', url($this->details['url']))
            ->line('Thank you for using our application!')
            ->line('Contact this number if youre cannot verify');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
