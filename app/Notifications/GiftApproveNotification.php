<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GiftApproveNotification extends Notification
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
            'message' =>  'has been informed that you received a gift',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'user_id' => $this->fromUser->id,
            'username' => $this->fromUser->username,
            'post_image' => $this->gift->main_image,
            'action' => [
                'gratitude' => [
                'url' => url('/api/v1/gratitudes'),
                'text' => 'Say Thank you'
                ]
            ],
            'show' => url('/api/v1/gifts/' . $this->gift->id),
            'type' => 'gift approved',
        ];
    }
}
