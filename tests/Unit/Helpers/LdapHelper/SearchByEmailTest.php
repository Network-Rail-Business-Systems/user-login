<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use NetworkRailBusinessSystems\UserLogin\Http\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SearchByEmailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

//        $this->useLdapEmulator(); //TODO setting up connection and users
    }

    public function testFindsEmails(): void
    {
        $results = collect(LdapHelper::searchByEmail('g'))
            ->pluck('givenname')
            ->flatten();

        $this->assertContains('Gandalf', $results);

        $this->assertContains('Gimli', $results);
    }

    public function testLimitsResults(): void
    {
        $this->assertCount(1, LdapHelper::searchByEmail('g', 1));
    }

    public function testMergesSelect(): void
    {
        $this->assertArrayHasKey(
            'samaccountname',
            LdapHelper::searchByEmail('g', 5, ['samaccountname'])[0],
        );
    }
}
