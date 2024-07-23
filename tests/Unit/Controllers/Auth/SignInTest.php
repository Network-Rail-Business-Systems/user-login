<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth\LoginRequest;
use NetworkRailBusinessSystems\UserLogin\Models\User;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SignInTest extends TestCase
{
    protected LoginController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createLdapUser();

        $this->controller = new LoginController();
    }

    public function testSignsIn(): void
    {
        User::factory()->create([
            'username' => 'gandalf',
            'password' => bcrypt('secret'),
        ]);

        $credentials = [
            'samaccountname' => 'gandalf',
            'password' => 'secret',
        ];
        
//  $this->attempt();

        $this->assertTrue(Auth::check());
    }

    protected function attempt($fail = false): RedirectResponse
    {
        return $this->controller->signIn(
            new LoginRequest([
                'username' => 'gandalf',
                'password' => 'secret',
            ]),
        );
    }
}
