<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GiftNotification extends Notification
{
    use Queueable;


    public $fromUser;
    public $gift;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fromUser, $gift)
    {
        $this->fromUser = $fromUser;
        $this->gift = $gift;
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
            'message' => $this->fromUser->username . ' sent you a gift "' . $this->gift->title . '"',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/gifts/' . $this->gift->id),
                'text' => 'Show Gift'
            ]
        ];
    }
}
