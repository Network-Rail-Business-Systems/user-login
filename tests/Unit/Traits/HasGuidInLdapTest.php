<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Traits;

use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;
use NetworkRailBusinessSystems\UserLogin\Traits\HasGuidInLdap;

class HasGuidInLdapTest extends TestCase
{
    public function testRetrieveUniqueIdentifierFromLdap()
    {
        $ldapModelMock = $this->mock('alias:LdapRecord\Models\ActiveDirectory\User');

        $ldapModelMock->shouldReceive('getAttributeValue')
            ->with('objectguid')
            ->andReturn(['some-guid-value']);

        $ldapModelMock->shouldReceive('query->select->where->first')
            ->andReturn($ldapModelMock);

        $user = new class
        {
            use HasGuidInLdap;
        };

        $guid = $user::uniqueIdentifier('test-user');

        $this->assertEquals('some-guid-value', $guid);
    }
}
