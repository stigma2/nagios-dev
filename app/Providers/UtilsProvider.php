<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UtilsProvider extends ServiceProvider
{
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
     * Register the application services.Å
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('App\Interfaces\NagiosInterface', 'App\Utils\Nagios');
    }
}
