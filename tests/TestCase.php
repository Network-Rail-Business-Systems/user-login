<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use Illuminate\Support\Facades\Config;
use Laracasts\Flash\FlashServiceProvider;
use LdapRecord\Configuration\ConfigurationException;
use LdapRecord\Connection;
use LdapRecord\Container;
use NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider;
use NetworkRailBusinessSystems\UserLogin\Tests\Models\User;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @throws ConfigurationException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $model = \NetworkRailBusinessSystems\UserLogin\Tests\Models\User::class;

        config()->set('user-login.local-model', $model);
        config()->set('user-login.auth-identifier', 'username');

        $this->useDatabase();

        $this->setUpRoutes();

        $this->createLocalUser();

        $connection = new Connection([
                'hosts' => ['corp.ukrail.net'],
                'username' => 'null',
                'password' => 'null',
                'port' => 389,
                'base_dn' => 'dc=corp,dc=ukrail,dc=net',
                'timeout' => 5,
                'use_ssl' => false,
                'use_tls' => false,
        ]);

        Container::addConnection($connection);
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
            FlashServiceProvider::class,
        ];
    }

    protected function setUpRoutes(): void
    {
        Config::set('user-login.view', 'gov-uk-login');
        $router = app('router');
        $router->userLogin();
    }

    protected function useDatabase(): void
    {
        config()->set('database.default', 'sqlite');

        $this->loadMigrationsFrom(__DIR__.'/../tests/Migrations');
    }

    public function createLocalUser(): void
    {
        User::factory()->create([
            'username' => 'gandalf',
            'email' => 'gandalf.stormcrow@example.com',
            'password' => Bcrypt('secret'),
            'guid' => 'testguid',
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
