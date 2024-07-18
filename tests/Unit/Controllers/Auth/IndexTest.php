<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth\LoginController;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class IndexTest extends TestCase
{
    protected LoginController $controller;

    protected View $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new LoginController();

    }

    public function testIndexWithGovUkLoginView()
    {
        $this->response = $this->controller->index();

        $this->assertInstanceOf(View::class, $this->response);

        $this->assertEquals(
            'What is your username?',
            $this->response->getData()['questions'][0]->label);

        $this->assertEquals(
            'What is your password?',
            $this->response->getData()['questions'][1]->label
        );

        $this->assertEquals(
            route('login'),
            $this->response->getData()['action'],
        );

        $this->assertEquals(
            'user-login::gov-uk-login',
            $this->response->name(),
        );
    }

    public function testIndexWithOtherLoginView()
    {
        Config::set('user-login.view', 'other-login');

        $this->response = $this->controller->index();

        $this->assertInstanceOf(View::class, $this->response);

        $this->assertEquals([
            'username' => [
                    'name' => 'username',
                    'label' => 'What is your username?',
                    'hint' => "The username you use to access your Windows device, such as jdoe3.",
            ],
            'password' => [
                    'name' => 'password',
                    'label' => 'What is your password?',
                    'hint' => "The password you use to access your Windows device.",
            ]],
            $this->response->getData()['questions']
        );

        $this->assertEquals(
            route('login'),
            $this->response->getData()['action'],
        );

        $this->assertEquals(
            'Sign in',
            $this->response->getData()['buttonLabel'],
        );

        $this->assertEquals(
            'user-login::other-login',
            $this->response->name(),
        );
    }
}
