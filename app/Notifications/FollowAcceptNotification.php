<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowAcceptNotification extends Notification
{
    use Queueable;

    public $fromUser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'message' =>  'accept your follow request',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/profile/5' . $this->fromUser->id),
                'text' => 'View Profile'
            ]
        ];
    }
}
