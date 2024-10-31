<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use NetworkRailBusinessSystems\UserLogin\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SearchByNameTest extends TestCase
{
    public function testFindsByName(): void
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('query->where->orWhere->andFilter->select->limit->get')
                ->andReturn([]);
        });

        $this->assertEquals(
            [],
            LdapHelper::searchByName('r'),
        );
    }
}
