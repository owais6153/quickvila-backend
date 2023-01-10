<?php

namespace App\Notifications\Customer;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class VerificationRequestStatus extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    protected $reason = '';
    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $msg = '';
        $subject = '';
        if($notifiable->is_identity_card_verified){
            $msg = 'Your request for identity card verification is accepted. Your account is now verified.';
            $subject = 'Your request for identity card verification is accepted';
        }
        else{
            $msg = 'Your request for identity card verification is rejected';
            $subject = 'Your request for identity card verification is rejected.';
            if($this->reason != '') $msg .= ' for the reason given below. <h4>Reason: ' . $this->reason . '</h4>';
            else $msg .= '.';
        }
        return (new MailMessage)
                    ->subject($subject . ' - ' .  env('MAIL_FROM_NAME'))
                    ->greeting('Hi ' . $notifiable->name . '!')
                    ->line(new HtmlString($msg))
                    ->line('Thank you for using our application!');
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
            //
        ];
    }
}
