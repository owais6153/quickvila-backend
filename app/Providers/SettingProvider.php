<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use View;
use Config;
use Illuminate\Support\Facades\Schema;

class SettingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            $emailSettings = getSetting('email');
            foreach($emailSettings as $key => $val){
                Config::set("mail.mailers.smtp.$key", $val);
            }
        }

    }
}
