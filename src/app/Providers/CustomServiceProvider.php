<?php

namespace NetworkRailBusinessSystems\UserLogin\Providers;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\ServiceProvider;
use LdapRecord\Laravel\LdapServiceProvider as BaseLdapServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(BaseLdapServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/ldap.php', 'ldap'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/auth.php', 'auth'
        );
    }

    public function boot()
    {
        // Boot methods if necessary
    }
}
