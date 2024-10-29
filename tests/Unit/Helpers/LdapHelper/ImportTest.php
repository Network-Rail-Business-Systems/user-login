<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use NetworkRailBusinessSystems\UserLogin\Http\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class ImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

//        $this->useLdapEmulator(); //TODO setting up connection and users
    }

    public function testThrowsExceptionWhenNotInDirectory(): void
    {
        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage(
            'Import cancelled; no User was found with the e-mail "ringwraith@example.com" in Active Directory',
        );

        LdapHelper::import('ringwraith@example.com');
    }

    public function testImportsUser(): void
    {
        LdapHelper::import('peregrin.took@example.com');

        $this->assertDatabaseHas('users', [
            'username' => 'pippin',
        ]);
    }

    public function testThrowsExceptionWhenUserMissing(): void
    {
        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage(
            'Import failed; no User was found with the e-mail "Peregrin.Took@example.com" in this system',
        );

        // This test works because SQLITE is case-sensitive!
        LdapHelper::import('Peregrin.Took@example.com');
    }
}
