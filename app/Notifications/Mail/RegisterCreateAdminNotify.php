<?php

namespace App\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterCreateAdminNotify extends Notification
{
    use Queueable;
    public $admin;
    public $user;


    /**
     * Create a new notification instance.
     */
    public function __construct($admin, $user)
    {
        $this->admin = $admin;
        $this->user = $user;
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
                    ->greeting('Hello '.$this->admin->name)
                    ->subject('Account created in ' . config('app.name').' Username:'.$this->user->name)
                    ->line('A new user has been created successfully!!')
                    ->line('Name: '. $this->user->name)
                    ->line('Email: '. $this->user->email)
                    ->line('Phone: '. $this->user->phone)
                    ->action('Visit Here', \Config::get('app.app_domain_url')."admin/login")
                    ->line('Thank you for using our application!');
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
