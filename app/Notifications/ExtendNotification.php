<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExtendNotification extends Notification
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
            'header' => '14 days passed.',
            'message' => 'Extend for more 7 days or release the wish for other followers',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'post_id' => $this->gift->id,
            'post_image' => $this->gift->main_image,
            'username' => $this->fromUser->username,
            'action' => [
                'extend' => [
                    'url' => url('/api/v1/wish/' . $this->gift->id . '/extend'),
                    'text' => 'extend',
                ],
                'release' => [
                    'url' => url('/api/v1/reserve/' . $this->gift->id . '/release'),
                    'text' => 'release',
                ]
            ]
        ];
    }
}
