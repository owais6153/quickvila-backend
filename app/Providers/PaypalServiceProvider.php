<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class PaypalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            $paymentsettings = getSetting('payment');

            $this->app->singleton('paypal', function () use($paymentsettings) {
                $apiContext = new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                        $paymentsettings['paypal_client_id'],
                        $paymentsettings['paypal_secret']
                    )
                );

                $apiContext->setConfig(
                    array(
                        'mode' => $paymentsettings['paypal_mode'],
                        'log.LogEnabled' => true,
                        'log.FileName' => storage_path('logs/paypal.log'),
                        'log.LogLevel' => env('APP_ENV') ==  'local'?  'DEBUG' : 'INFO',
                    )
                );

                return $apiContext;
            });

        }

    }
}
