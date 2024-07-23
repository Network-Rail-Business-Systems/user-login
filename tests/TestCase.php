<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use LdapRecord\Models\DirectoryServer\User as LdapUser;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use NetworkRailBusinessSystems\UserLogin\Models\User;
use NetworkRailBusinessSystems\UserLogin\Providers\CustomServiceProvider;
use NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(CustomServiceProvider::class);

        DirectoryEmulator::setup();

        $this->setUpAuthConfig();

        $this->useDatabase();

        $this->setUpFactories();

        $this->setUpRoutes();
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
            CustomServiceProvider::class,
        ];
    }

    protected function setUpRoutes(): void
    {
        Config::set('user-login.view', 'gov-uk-login');
        $router = app('router');
        $router->userLogin();
    }

    protected function setUpAuthConfig(): void
    {
        config()->set('app.name', 'Test Application');
        config()->set('app.env', 'testing');

        config()->set('auth.defaults.guard', 'web');

        config()->set('auth.guards.web', [
            'driver' => 'session',
            'provider' => 'ldap',
        ]);

        config()->set('auth.providers.users', [
                'driver' => 'eloquent',
                'model' => User::class,
        ]);

        config()->set('auth.providers.ldap', [
            'driver' => 'ldap',
            'model' => LdapUser::class,
            'database' => [
                'model' => User::class,
                'sync_passwords' => false,
                'sync_attributes' => [
                    'first_name' => 'givenname',
                    'last_name' => 'sn',
                    'email' => 'mail',
                    'username' => 'samaccountname',
                ],
                'sync_existing' => [
                    'username' => 'samaccountname',
                ],
            ],
        ]);
    }

    public function createLdapUser(): void
    {
       \LdapRecord\Models\ActiveDirectory\User::create([
            'cn' => 'Gandalf Stormcrow',
            'givenname' => 'Gandalf',
            'sn' => 'Stormcrow',
            'mail' => 'gandalf.stormcrow@example.com',
            'samaccountname' => 'gandalf',
        ]);
    }

    protected function useDatabase(): void
    {
        config()->set('database.default', 'sqlite');

        $this->app->useDatabasePath(__DIR__ . '/../src/app/Database');

        $this->runLaravelMigrations();
    }

    protected function setUpFactories(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $factoryName = 'NetworkRailBusinessSystems\\UserLogin\\Database\\Factories\\' . class_basename($modelName) . 'Factory';
            return class_exists($factoryName) ? $factoryName : null;
        });
    }

    public function tearDown(): void
    {
        DirectoryEmulator::teardown();
        parent::tearDown();
    }
}

