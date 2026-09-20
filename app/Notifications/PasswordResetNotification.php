<?php

namespace App\Notifications;

use App\Mail\PasswordResetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): PasswordResetMail
    {
        $email = $notifiable->getEmailForPasswordReset();
        $url = url(route('password.reset', ['token' => $this->token, 'email' => $email], false));

        return (new PasswordResetMail($url))->to($email);
    }
}