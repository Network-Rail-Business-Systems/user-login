<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth\LoginController;
use NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth\LoginRequest;
use NetworkRailBusinessSystems\UserLogin\Models\User;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SignInTest extends TestCase
{
    protected LoginController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useLdapEmulator();

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
            'Sign in failed; please check your username and password and try again',
            flash()->messages->first()->message
        );
    }

    public function testSyncsExistingWhenUsernameWrong(): void
    {
        User::query()
            ->where('username', '=', 'gandalf')
            ->update([
                'username' => 'banralph',
                'guid' => 'kjsdakjsad',
            ]);

        $this->attempt();

        $this->assertDatabaseHas('users', [
            'username' => 'gandalf',
            'email' => 'gandalf.stormcrow@example.com',
        ]);
    }

    public function testSyncsExistingWhenEmailWrong(): void
    {
        User::query()
            ->where('username', '=', 'gandalf')
            ->update([
                'email' => 'banralph@example.com',
                'guid' => 'laskajsd',
            ]);

        $this->attempt();

        $this->assertDatabaseHas('users', [
            'username' => 'gandalf',
            'email' => 'gandalf.stormcrow@example.com',
        ]);
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
