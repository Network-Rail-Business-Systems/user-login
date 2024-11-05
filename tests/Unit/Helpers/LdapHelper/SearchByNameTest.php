<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use NetworkRailBusinessSystems\UserLogin\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;
use LdapRecord\Models\Collection;

class SearchByNameTest extends TestCase
{
    public function testFindsByName(): void
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('query->where->orWhere->andFilter->select->limit->get')
                ->once()
                ->andReturn(new Collection);
        });

        $this->assertEquals(
            new Collection([]),
            LdapHelper::searchByName('r'),
        );
    }
}
