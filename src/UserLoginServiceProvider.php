<?php

namespace NetworkRailBusinessSystems\UserLogin;

use Illuminate\Support\ServiceProvider;

class UserLoginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/user-login-config.php',
            'user-login'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/user-login-config.php' => config_path('user-login.php'),
        ], 'user-login');
    }
}
