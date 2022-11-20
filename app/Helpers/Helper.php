<?php

// Roles
function Admin(){
    return 'Admin';
}
function Manager(){
    return 'Manager';
}
function Customer(){
    return 'Customer';
}
function Store(){
    return 'Store';
}
function Rider(){
    return 'Rider';
}

// Email
function sendEmail($f = array(), $s =  array(), $user, $subject, $body, $type = 'text/html'){
    if(Config::get('mail.mailers.smtp.should_send') === true){
        try{
            Mail::send($f,$s, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject($subject)
                    ->setBody($body, $type);
            });
        }
        catch (\Throwable $e){
            throw new Exception('Failed to send Email', 500);
        }
    }
}

// Settings
function getSetting($key){
    $setting = \App\Models\Setting::where('key', $key)->first();
    if(!empty($setting)){
        if($setting->value){
          return $setting = unserialize($setting->value);
        }
    }
    return false;
}
