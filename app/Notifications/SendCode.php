<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Config;

class SendCode extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if(Config::get('mail.mailers.smtp.should_send') === true){

            $code = $notifiable->codes->where('type', 'email')->first();

            return (new MailMessage)
                    ->subject('Verify Your Account - ' . env('MAIL_FROM_NAME'))
                    ->greeting("Hi $notifiable->name!")
                    ->line("Please verify your account")
                    ->line(new HtmlString("<h3>Your Verification Code is: <span style='font-size: 20px'>$code->code</span></h3>"))
                    ->line('Thank you for using our application!');
        }
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
