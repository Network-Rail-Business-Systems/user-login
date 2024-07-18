<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use Illuminate\Support\Facades\Config;
use NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRoutes();
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
        ];
    }


    protected function setUpRoutes(): void
    {
        Config::set('user-login.view', 'gov-uk-login');

        $router = app('router');
        $router->userLogin();
    }
}
