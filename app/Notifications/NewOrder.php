<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrder extends Notification
{
    use Queueable;
    private $details;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
    
        $this->details=$details;
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


   ->subject($this->details['subject'])
            ->greeting($this->details['greeting'])

            ->line($this->details['body'])

            ->line($this->details['id'])
            ->line($this->details['topic'])
            ->line($this->details['subjectorder'])
            ->line($this->details['urgency'])
            ->line($this->details['document'])
           ->line($this->details['pages'])
            ->line($this->details['document'])
            ->line($this->details['pages'])
            ->line($this->details['total'])

            ->line($this->details['thanks'])
             ->line($this->details['regards'])
            ->line($this->details['company']);








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
