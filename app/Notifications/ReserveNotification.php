<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReserveNotification extends Notification
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
            'message' => 'wish is reserved you have 14 days to grant it',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'post_id' => $this->gift->id,
            'post_image' => $this->gift->main_image,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/reserve/' . $this->gift->id . '/granted'),
                'text' => 'grant'
            ],
            'type' => 'reserve'
        ];
    }
}
