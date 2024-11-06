<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Traits;

use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;
use NetworkRailBusinessSystems\UserLogin\Traits\HasGuidInLdap;

class HasGuidInLdapTest extends TestCase
{
    public function testRetrieveUniqueIdentifierFromLdap()
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('getAttributeValue')
                ->with('objectguid')
                ->andReturn(['some-guid-value']);

            $mock->shouldReceive('query->select->where->first')
                ->andReturn($mock);
        });

        $user = new class {
            use HasGuidInLdap;
        };

        $guid = $user::uniqueIdentifier('test-user');

        $this->assertEquals('some-guid-value', $guid);
    }
}
