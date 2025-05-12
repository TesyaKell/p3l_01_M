<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $token;
    private $role;
    public function __construct($token, $role)
    {
        $this->token = $token;
        $this->role = $role;
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
            ->greeting('Hello!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun anda.')
            ->action('Reset Password', url("/resetPassword/" . $this->role . "/" . $this->token . "?email=" . $notifiable->getEmailForPasswordReset()))
            ->line('Link reset password akan kadaluarsa dalam 60 menit.')
            ->line('Jika anda tidak merasa melakukan permintaan reset password, abaikan email ini.');
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
