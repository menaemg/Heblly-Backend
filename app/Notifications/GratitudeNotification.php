<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GratitudeNotification extends Notification
{
    use Queueable;

    public $fromUser;
    public $gratitude;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fromUser, $gratitude)
    {
        $this->fromUser = $fromUser;
        $this->gratitude = $gratitude;
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
            'message' => 'gratitude you',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'username' => $this->fromUser->username,
            'post_image' => $this->gratitude->main_image,
            'action' => [
                'url' => url('/api/v1/gratitudes/' . $this->gratitude->id),
                'text' => 'Show Gratitude'
            ]
        ];
    }
}
