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
        $this->attempt();

        $this->assertTrue(Auth::check());
    }

    public function testFlashesSuccess(): void
    {
        $this->attempt();

        $this->assertEquals('success', flash()->messages->first()->level);
    }

    public function testRedirectsOnSuccess(): void
    {
        $this->assertEquals(route('dashboard'), $this->attempt()->getTargetUrl());
    }

    public function testFlashesFailure(): void
    {
        $this->attempt(true);

        $this->assertEquals('danger', flash()->messages->first()->level);
    }

    public function testRedirectsOnFailure(): void
    {
        $this->assertEquals(route('sign-in'), $this->attempt(true)->getTargetUrl());
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
                'username' => $fail === false ? 'gandalf' : 'EvilMonkey',
                'password' => $fail === false ? 'secret' : 'bananas',
            ]),
        );
    }
}
