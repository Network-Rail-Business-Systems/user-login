<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth\LoginController;
use NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth\LoginRequest;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SignInTest extends TestCase
{
    protected LoginController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new LoginController;
    }

    public function testSignsIn(): void
    {
        $this->attempt();

        $this->assertTrue(Auth::check());
    }

    public function testFlashesSuccess(): void
    {
        $this->attempt();

        $this->assertEquals('success', flash()->messages->first()->level);

        $this->assertEquals('You have successfully signed in', flash()->messages->first()->message);
    }

    public function testFlashesFailure(): void
    {
        $this->attempt(true);

        $this->assertEquals('danger', flash()->messages->first()->level);

        $this->assertEquals(
            'Sign-in failed; check your details and try again',
            flash()->messages->first()->message
        );
    }

    protected function attempt($fail = false): RedirectResponse
    {
        return $this->controller->signIn(
            new LoginRequest([
                'username' => $fail === false ? 'gandalf' : 'FakeUsername',
                'password' => $fail === false ? 'secret' : 'password',
            ]),
        );
    }
}
