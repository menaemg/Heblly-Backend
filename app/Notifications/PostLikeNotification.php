<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostLikeNotification extends Notification
{
    use Queueable;


    public $fromUser;
    public $post;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fromUser, $post)
    {
        $this->fromUser = $fromUser;
        $this->post = $post;
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
            'message' => $this->fromUser->username . ' like your post',
            'image' =>   $this->fromUser->profile ? $this->fromUser->profile->avatar_url : null,
            'username' => $this->fromUser->username,
            'action' => [
                'url' => url('/api/posts/' . $this->post->id),
                'text' => 'Show Post'
            ]
        ];
    }
}
