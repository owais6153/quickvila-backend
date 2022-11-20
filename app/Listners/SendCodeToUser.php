<?php

namespace App\Listners;

use App\Events\UserEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\UserCode;

class SendCodeToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setting = getSetting('general');
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserEvent  $event
     * @return void
     */
    public function handle(UserEvent $event)
    {
        if(!empty($this->setting)){
        $user = $event->user;
        $user->codes()->delete();
        $user_code = UserCode::create([
            'code' => rand(100000, 999999),
            'expires_at' => date('Y-m-d'),
            'user_id' => $user->id,
            'type' => $this->setting['default_verification_method'],
        ]);

        if($this->setting['default_verification_method'] == 'email')
            $user->sendCodeByEmail();
        else if($this->setting['default_verification_method'] == 'phone')
            $user->sendCodeByPhone();
    }


    }
}
