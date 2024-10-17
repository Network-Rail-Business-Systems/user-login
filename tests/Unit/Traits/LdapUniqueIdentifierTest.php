<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Traits;

use Mockery as ldapMocker;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;
use NetworkRailBusinessSystems\UserLogin\Traits\LdapUniqueIdentifier;

class LdapUniqueIdentifierTest extends TestCase
{
    public function testRetrieveUniqueIdentifierFromLdap()
    {
        $ldapModelMock = ldapMocker::mock('alias:LdapRecord\Models\ActiveDirectory\User');

        $ldapModelMock->shouldReceive('getAttributeValue')
            ->with('objectguid')
            ->andReturn(['some-guid-value']);

        $ldapModelMock->shouldReceive('query->where->first')
            ->andReturn($ldapModelMock);

        $user = new class
        {
            use LdapUniqueIdentifier;
        };

        $guid = $user::uniqueIdentifier('test-user');

        $this->assertEquals('some-guid-value', $guid);
    }
}
