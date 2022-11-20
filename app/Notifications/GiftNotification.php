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
            'message' =>'it seems that your wish granted by',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'user_id' => $this->fromUser->id,
            'username' => $this->fromUser->username,
            'post_image' => $this->gift->main_image,
            'action' => [
                'yes' => [
                    'url' => url('/api/gifts/' . $this->gift->id . '/approve'),
                    'text' => 'Yes'
                ],
                'ignore' => [
                    'url' => url('/api/gifts/' . $this->gift->id . '/reject'),
                    'text' => 'Ignore'
                ],
            ],
            'show' => url('/api/gifts/' . $this->gift->id),
            'approve' => url('/api/gifts/' . $this->gift->id . '/approve'),
            'reject' => url('/api/gifts/' . $this->gift->id . '/reject'),
            'type' => 'gift received',
        ];
    }
}
