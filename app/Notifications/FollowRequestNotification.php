<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FollowRequestNotification extends Notification
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
            'message' => $this->fromUser->username . ' wants to follow you',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/follow/accept/' . $this->fromUser->id),
                'text' => 'Accept Follow Request'
            ]
        ];
    }
}
