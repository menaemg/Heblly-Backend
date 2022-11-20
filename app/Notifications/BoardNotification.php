<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BoardNotification extends Notification
{
    use Queueable;


    public $fromUser;
    public $board;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fromUser, $board)
    {
        $this->fromUser = $fromUser;
        $this->board = $board;
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
            'message' => 'is requesting to post this in your board',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'post_id' => $this->board->id,
            'post_image' => $this->board->main_image,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/boards/' . $this->board->id . '/allow'),
                'text' => 'Allow'
            ]
        ];
    }
}
