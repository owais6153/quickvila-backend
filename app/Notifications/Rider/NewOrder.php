<?php

namespace App\Notifications\Rider;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioWhatsappChannel;
use Twilio\Rest\Client;
use Config;

class NewOrder extends Notification implements ShouldQueue
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
        return TwilioWhatsappChannel::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTwilioWhatsapp($notifiable)
    {
        if($this->settings['should_send']){
            $account_sid = $this->settings['sid'];
            $auth_token = $this->settings['token'];
            $number = $this->settings['number'];
            $client = new Client($account_sid, $auth_token);
            $sendTo = $notifiable->phone;

            $message = $client->messages
            ->create("whatsapp:$number", // to
                     array(
                         "from" => "whatsapp:$sendTo",
                         "body" => "Your Yummy Cupcakes Company order of 1 dozen frosted cupcakes has shipped and should be delivered on July 10, 2019. Details: http://www.yummycupcakes.com/"
                     )
            );
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
