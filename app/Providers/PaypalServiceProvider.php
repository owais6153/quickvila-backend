<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


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
            $this->app->singleton('paypal', function () {
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                    env('PAYPAL_CLIENT_ID'),
                    env('PAYPAL_API_SECRET')
                )
            );

            $apiContext->setConfig(
                array(
                    'mode' => env('PAYPAL_MODE'),
                    'log.LogEnabled' => true,
                    'log.FileName' => storage_path('logs/paypal.log'),
                    'log.LogLevel' => env('APP_ENV') ==  'local'?  'DEBUG' : 'INFO',
                )
            );

            return $apiContext;
        });
    }
}
