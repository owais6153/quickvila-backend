<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioChannel;
use Twilio\Rest\Client;
use Config;


class SendCodeByPhone extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $settings;
    public function __construct()
    {
        $this->settings = getSetting('sms');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return TwilioChannel::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTwilioSMS($notifiable)
    {
        if($this->settings['should_send']){
            $account_sid = $this->settings['sid'];
            $auth_token = $this->settings['token'];
            $number = $this->settings['number'];
            $service_id = $this->settings['messaging_service'];
            $client = new Client($account_sid, $auth_token);
            $code = $notifiable->codes->where('type', 'phone')->first();
            $client->messages->create($notifiable->phone,
                    ['messagingServiceSid' => $service_id, 'body' => "Your verification code is: $code->code, Please ignore this message if you did'nt request this code."] );
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
