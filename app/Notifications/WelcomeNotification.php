<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

   
    public function __construct()
    {
        //
    }

    
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . '! 🚀')
            ->greeting('Hi ' . $notifiable->name . '!')
            ->line('We\'re thrilled to have you join us! Your account has been successfully created and is ready to use.')
            ->line('You can now log in to access your dashboard and manage your employee records.')
            ->line('**Your Credentials:**')
            ->line('**Email:** ' . $notifiable->email)
            ->action('Log In Now', url('/login'))
            ->line('If you have any questions, feel free to reply to this email.')
            ->salutation('Best regards, The ' . config('app.name') . ' Team');
    }

    
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

