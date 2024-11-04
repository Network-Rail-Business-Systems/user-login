<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use NetworkRailBusinessSystems\UserLogin\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\Models\User;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class ImportTest extends TestCase
{
    public function testThrowsExceptionWhenNotInDirectory(): void
    {
        $this->runMock(true);

        $this->expectException(ErrorException::class);

        $this->assertEquals(
            'Import cancelled; no User was found with the e-mail "ringwraith@example.com" in Active Directory',
            LdapHelper::import('ringwraith@example.com'),
        );
    }

    public function testImportsUser(): void
    {
        $this->runMock();

        $this->assertEquals(
            'peregrin.took@example.com',
            LdapHelper::import('peregrin.took@example.com')->email,
        );
    }

    public function testThrowsExceptionWhenUserMissing(): void
    {
        $this->runMock();

        $this->expectException(ErrorException::class);

        $this->assertEquals(
            'Import failed; no User was found with the e-mail "Peregrin.Took@example.com"',
            LdapHelper::import('Peregrin.Took@example.com')
        );
    }

    public function runMock(bool $returnNull = false): void
    {
        if ($returnNull === false) {
            $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
                $mock->shouldReceive('query->where->andFilter->select->limit->get')
                    ->once()
                    ->andReturn([Model::class]);
            });

            $this->mock('overload:Illuminate\Support\Facades\Artisan', function ($mock) {
                $mock->shouldReceive('call')
                    ->once()
                    ->andReturnUsing(function () {
                        User::factory()->create([
                            'email' => 'peregrin.took@example.com',
                        ]);
                    });
            });
        } else {
            $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
                $mock->shouldReceive('query->where->andFilter->select->limit->get')
                    ->once()
                    ->andReturn([]);
            });

            $this->mock('overload:Illuminate\Support\Facades\Artisan', function ($mock) {
                $mock->shouldReceive('call')
                    ->once()
                    ->andReturnNull();
            });
        }
    }
}
