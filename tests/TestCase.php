<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Laracasts\Flash\FlashServiceProvider;
use LdapRecord\Connection;
use LdapRecord\Container;
use LdapRecord\Laravel\LdapAuthServiceProvider;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use NetworkRailBusinessSystems\UserLogin\Models\User;
use NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->useDatabase();

        $this->setUpFactories();

        $this->setUpLdapConfig();

        $this->useLdapConnection();

        $this->setUpAuthConfig();

        $this->setUpRoutes();
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
            LdapAuthServiceProvider::class,
            FlashServiceProvider::class,
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

        config()->set('auth.defaults.guard', 'ldap');

        config()->set('auth.guards.ldap', [
            'driver' => 'session',
            'provider' => 'ldap',
        ]);
    }

    protected function setUpLdapConfig(): void
    {
        config()->set('ldap.connections', [
            'default' => [
                'hosts' => ['127.0.0.1'],
                'username' => 'cn=user,dc=local,dc=com',
                'password' => 'secret',
                'port' => 389,
                'base_dn' => 'dc=local,dc=com',
                'timeout' => 5,
                'use_ssl' => false,
                'use_tls' => false,
            ],
        ]);
    }

    protected function useLdapConnection(): void
    {
        $connection = new Connection(config('ldap.connections.default'));

        Container::addConnection($connection);
    }

    protected function useDatabase(): void
    {
        config()->set('database.default', 'sqlite');

        $this->loadMigrationsFrom(__DIR__.'/../src/Database/migrations');
    }

    protected function setUpFactories(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $factoryName = 'NetworkRailBusinessSystems\\UserLogin\\Database\\Factories\\'.class_basename($modelName).'Factory';

            return class_exists($factoryName) ? $factoryName : null;
        });
    }

    public function useLdapEmulator(): void
    {
        DirectoryEmulator::setup(
            config('ldap.default')
        );

        $username = 'gandalf';
        $password = 'secret';

        $ldapUser = $this->createLdapUser($username);

        $this->createLocalUser($username, $password, $ldapUser);

        $this->setUpEventListner();
    }

    protected function createLdapUser(string $username): LdapUser
    {
        return LdapUser::create([
            'cn' => 'Gandalf Stormcrow',
            'givenname' => 'Gandalf',
            'sn' => 'Stormcrow',
            'mail' => 'gandalf.stormcrow@example.com',
            'samaccountname' => $username,
        ]);
    }

    protected function createLocalUser(string $username, string $password, LdapUser $ldapUser): void
    {
        $user = new User;

        $user->first_name = 'Gandalf';
        $user->last_name = 'Stormcrow';
        $user->email = 'gandalf.stormcrow@example.com';
        $user->username = $username;
        $user->password = $password;
        $user->guid = $ldapUser->fresh()->getObjectGuid();
        $user->save();
    }

    protected function setUpEventListner()
    {
        Event::listen(
            function (Attempting $event) {
                static::setActingUser($event->credentials['samaccountname']);
            }
        );
    }

    protected static function setActingUser(string $username): void
    {
        Container::getDefaultConnection()->actingAs(
            LdapUser::findBy('samaccountname', strtolower($username)),
        );
    }

    public function tearDown(): void
    {
        DirectoryEmulator::teardown();
        parent::tearDown();
    }
}
