<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests;

use NetworkRailBusinessSystems\OracleApi\UserLoginServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            UserLoginServiceProvider::class,
        ];
    }
}
