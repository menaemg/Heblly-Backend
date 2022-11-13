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
            'message' => $this->fromUser->username . ' share in your board "' . $this->board->title . '"',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/boards/' . $this->board->id),
                'text' => 'Show Wish Board'
            ]
        ];
    }
}
