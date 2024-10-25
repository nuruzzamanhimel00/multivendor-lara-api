<?php

namespace App\Notifications\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerNotfyMailRegisCreate extends Notification
{
    use Queueable;

    public $owner;
    public $password;


    /**
     * Create a new notification instance.
     */
    public function __construct( $owner, $password)
    {

        $this->owner = $owner;
        $this->password = $password;
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
                    ->greeting('Hello '.$this->owner->name)
                    ->subject('Your Account created in ' . config('app.app_domain_url'))
                    ->line('Your account has been created successfully!!')
                    ->line('Name: '. $this->owner->name)
                    ->line('Email: '. $this->owner->email)
                    ->line('Password: '. $this->password)
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
