<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        //
    }
    public function register() {
        $this->app->bind(\App\Interfaces\AuthenticationInterface::class, App\Implementations\AuthenticationImplementation::class);
    }
}
