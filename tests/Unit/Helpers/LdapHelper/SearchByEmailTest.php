<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use NetworkRailBusinessSystems\UserLogin\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SearchByEmailTest extends TestCase
{
    public function testFindsEmails(): void
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('query->where->andFilter->select->limit->get')
                ->once()
                ->andReturn([]);
        });

        $this->assertEquals(
            [],
            LdapHelper::searchByEmail('s'),
        );
    }
}
