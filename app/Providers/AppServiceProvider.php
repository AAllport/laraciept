<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LinearApi\Client as LinearClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LinearClient::class,fn($app)=> new LinearClient(config('services.linear.key')));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
