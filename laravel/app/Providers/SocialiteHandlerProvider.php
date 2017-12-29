<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Socialite\SocialiteHandler;

class SocialiteHandlerProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SocialiteHandler::class, function ($app) {
            return new SocialiteHandler;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [SocialiteHandler::class];
    }
}
