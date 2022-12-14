<?php
namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class TwilioSMSChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTwilioSMS($notifiable);

    }
}
