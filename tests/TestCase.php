<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use Illuminate\Support\Facades\Config;
use LdapRecord\Laravel\Testing\DirectoryEmulator;
use LdapRecord\Models\DirectoryServer\User;
use LdapRecord\Models\Model;
use NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpRoutes();
        $this->setUpLdapConnection();
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

    protected function setUpLdapConnection(): void
    {
        DirectoryEmulator::setup();

        Config::set('ldap.connections.default', [
            'hosts' => ['ldap.example.com'],
            'base_dn' => 'dc=example,dc=com',
            'username' => 'cn=admin,dc=example,dc=com',
            'password' => 'password',
            'port' => 389,
            'use_ssl' => false,
            'use_tls' => false,
        ]);
    }

    public function createLdapUser($email = 'gandalf.stormcrow@example.com'): Model
    {
        return User::create([
            'cn' => 'john',
            'givenname' => 'John',
            'sn' => 'Smith',
            'mail' => $email,
        ]);
    }
}
