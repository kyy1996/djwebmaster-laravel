<?php

namespace App\Providers;

use Illuminate\Auth\TokenGuard;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        app("auth")->extend("apiToken", function (\Illuminate\Foundation\Application $app, $name, $config) {
            $config['name'] = $name;
            $guard          = new TokenGuard(app("auth")->createUserProvider($config['provider'] ?? null), $this->app['request'],
                'token', 'token');
            $app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
