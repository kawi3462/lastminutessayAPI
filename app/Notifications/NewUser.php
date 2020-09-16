<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUser extends Notification
{
    use Queueable;
    private $userdetails;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userdetails)
    {
        $this->userdetails=$userdetails;
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)


            ->subject($this->userdetails['subject'])
            ->greeting($this->userdetails['greeting'])

            ->line($this->userdetails['body'])

            ->line($this->userdetails['phone'])
            ->line($this->userdetails['email'])
            ->line($this->userdetails['password'])
            ->line($this->userdetails['country'])
            ->line($this->userdetails['thanks']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
