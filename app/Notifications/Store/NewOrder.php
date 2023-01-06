<?php

namespace App\Notifications\Store;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\FirebaseChannel;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Appy\FcmHttpV1\FcmGoogleHelper;


class NewOrder extends Notification
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
        return FirebaseChannel::class;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFirebase($notifiable)
    {
        $url = config('fcm_config.fcm_api_url');
        $oauthToken = FcmGoogleHelper::configureClient();
        $headers = [
            'Authorization' => 'Bearer ' . $oauthToken,
            'Content-Type' =>  'application/json',
        ];
        $client = new Client();
        $data = [
            "message" => [
                "token" => $notifiable->devicetokens->first()->token,
                "webpush" => [
                    "notification" => [
                        "title" => 'New Order Received',
                        "body" => 'Null',
                        "icon" => '',
                        "click_action" => ''
                    ],
                ]
            ]
        ];
        $encodedData = json_encode($data);
        try {
            $request = $client->post($url, [
                'headers' => $headers,
                "body" => $encodedData,
            ]);
            $response = $request->getBody();
            return $response;
        } catch (Exception $e) {
            Log::error("[Notification] ERROR", [$e->getMessage()]);
            return $e;
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
