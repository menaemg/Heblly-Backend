<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordCode extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
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

    // /**
    //  * Get the mail representation of the notification.
    //  *
    //  * @param  mixed  $notifiable
    //  * @return \Illuminate\Notifications\Messages\MailMessage
    //  */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->subject('Reset Password Code')
    //                 ->greeting("Hello, $notifiable->username!")
    //                 ->line('You are receiving this email because we received a password reset request for your account.')
    //                 ->action($this->code, '#')
    //                 ->line('This code valid for one hour from now, Thank you for using our application!')
    //                 ->line("Dont't Share This Code With Anyone!");
    // }


    public function build()
    {
        return $this->markdown('auth.send-code-reset-password');
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
