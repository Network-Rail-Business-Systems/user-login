<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Laracasts\Flash\FlashServiceProvider;
use NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider;
use NetworkRailBusinessSystems\UserLogin\Tests\Models\User;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('user-login.model', User::class);

        $this->useDatabase();

        $this->setUpFactories();

        $this->setUpRoutes();

        $this->createLocalUser();
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

    protected function setUpFactories(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $factoryName = 'NetworkRailBusinessSystems\\UserLogin\\Tests\\Factories\\'.class_basename($modelName).'Factory';

            return class_exists($factoryName) ? $factoryName : null;
        });
    }

    public function createLocalUser(): void
    {
        User::factory()->create([
            'username' => 'gandalf',
            'email' => 'gandalf.stormcrow@example.com',
            'password' => Bcrypt('secret'),
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
