<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Config;

class SendCodeByEmail extends Notification implements ShouldQueue
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
        if(Config::get('mail.mailers.smtp.should_send')){

            $code = $notifiable->codes->where('type', 'email')->first();

            return (new MailMessage)
                    ->subject('Verify Your Account - ' . env('MAIL_FROM_NAME'))
                    ->greeting("Hi $notifiable->name!")
                    ->line("Please verify your account")
                    ->line(new HtmlString("<h3>Your Verification Code is: <br><span style='font-size: 25px; color: #f00'>$code->code</span></h3>"))
                    ->line("Please ignore this email if you did'nt request this code.")
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
