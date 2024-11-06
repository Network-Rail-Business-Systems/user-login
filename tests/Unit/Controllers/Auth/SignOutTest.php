<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth\LoginController;
use NetworkRailBusinessSystems\UserLogin\Tests\Models\User;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SignOutTest extends TestCase
{
    protected LoginController $controller;

    protected RedirectResponse $response;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        Auth::loginUsingId($this->user);

        $this->controller = new LoginController();
        $this->response = $this->controller->signOut();
    }

    public function testSignsOut(): void
    {
        $this->assertFalse(Auth::check());
    }

    public function testReturnsView(): void
    {
        $this->assertEquals(route('login'), $this->response->getTargetUrl());
    }
}
