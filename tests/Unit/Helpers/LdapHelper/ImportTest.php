<?php

namespace NetworkRailBusinessSystems\UserLogin\Tests\Unit\Helpers\LdapHelper;

use Illuminate\Database\Eloquent\Model;
use NetworkRailBusinessSystems\UserLogin\Helpers\LdapHelper;
use NetworkRailBusinessSystems\UserLogin\Tests\Models\User;
use NetworkRailBusinessSystems\UserLogin\Tests\TestCase;

class ImportTest extends TestCase
{
    public function testThrowsExceptionWhenNotInDirectory(): void
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('query->where->andFilter->select->limit->get')
                ->andReturn([]);
        });

        $this->mock('overload:Illuminate\Support\Facades\Artisan', function ($mock) {
            $mock->shouldReceive('call')
                ->andReturnNull();
        });

        $this->expectException(\ErrorException::class);

        $this->assertEquals(
            'Import cancelled; no User was found with the e-mail "ringwraith@example.com" in Active Directory',
            LdapHelper::import('ringwraith@example.com'),
        );
    }

    public function testImportsUser(): void
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('query->where->andFilter->select->limit->get')
                ->andReturn([Model::class]);
        });

        $this->mock('overload:Illuminate\Support\Facades\Artisan', function ($mock) {
            $mock->shouldReceive('call')
                ->andReturnUsing(function () {
                    User::factory()->create([
                        'email' => 'peregrin.took@example.com',
                    ]);
                })->once();
        });

        $this->assertEquals(
            'peregrin.took@example.com',
            LdapHelper::import('peregrin.took@example.com')->email,
        );
    }

    public function testThrowsExceptionWhenUserMissing(): void
    {
        $this->mock('alias:LdapRecord\Models\ActiveDirectory\User', function ($mock) {
            $mock->shouldReceive('query->where->andFilter->select->limit->get')
                ->andReturn([Model::class]);
        });

        $this->mock('overload:Illuminate\Support\Facades\Artisan', function ($mock) {
            $mock->shouldReceive('call')
                ->andReturnUsing(function () {
                    User::factory()->create([
                        'email' => 'peregrin.took@example.com',
                    ]);
                })->once();
        });

        $this->expectException(\ErrorException::class);

        $this->assertEquals(
            'Import failed; no User was found with the e-mail "Peregrin.Took@example.com"',
            LdapHelper::import('Peregrin.Took@example.com')
        );
    }
}
