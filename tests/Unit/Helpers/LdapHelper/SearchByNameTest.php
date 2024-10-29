<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use NetworkRailBusinessSystems\UserLogin\Http\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class SearchByNameTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

//        $this->useLdapEmulator(); //TODO setting up connection and users
    }

    public function testFindsByTerm(): void
    {
        $results = collect(LdapHelper::searchByName('g'))
            ->pluck('givenname')
            ->flatten();

        $this->assertContains('Gandalf', $results);

        $this->assertContains('Gimli', $results);
    }

    public function testLimitsResults(): void
    {
        $this->assertCount(1, LdapHelper::searchByName('g', 1));
    }

    public function testMergesAdditionalSelects(): void
    {
        $this->assertArrayHasKey(
            'samaccountname',
            LdapHelper::searchByName('g', 5, ['samaccountname'])[0],
        );
    }
}
