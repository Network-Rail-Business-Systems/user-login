<?php

namespace NetworkRailBusinessSystems\UserLogin\Http\Helpers;

use App\Models\User;
use ErrorException;
use Illuminate\Support\Facades\Artisan;
use LdapRecord\Container;
use LdapRecord\Query\Builder;
use Throwable;

class LdapHelper
{
    public static function searchByEmail(string $term, int $limit = 5, array $select = []): array
    {
        return Container::getDefaultConnection()
            ->query()
            ->where('mail', 'starts_with', $term)
            ->andFilter(function (Builder $query) {
                $query->whereHas('givenname')->whereHas('sn');
            })
            ->select(array_merge(['givenname', 'sn', 'mail'], $select))
            ->limit($limit)
            ->get();
    }

    public static function searchByName(string $term, int $limit = 5, array $select = []): array
    {
        return Container::getDefaultConnection()
            ->query()
            ->where('givenname', 'starts_with', $term)
            ->orWhere('sn', 'starts_with', $term)
            ->andFilter(function (Builder $query) {
                $query->whereHas('givenname')->whereHas('sn');
            })
            ->select(array_merge(['givenname', 'sn', 'mail'], $select))
            ->limit($limit)
            ->get();
    }

    public static function import(string $email): User
    {
        if (empty(self::searchByEmail($email)) !== false) {
            throw new ErrorException(
                "Import cancelled; no User was found with the e-mail \"$email\" in Active Directory",
            );
        }

        Artisan::call('ldap:import', [
            'provider' => 'ldap',
            '--no-interaction',
            '--restore' => true,
            '--delete' => false,
            '--delete-missing' => false,
            '--filter' => "(mail={$email})",
        ]);

        try {
            return User::byEmail($email)->firstOrFail();
        } catch (Throwable $exception) {
            throw new ErrorException(
                "Import failed; no User was found with the e-mail \"$email\" in this system",
            );
        }
    }
}
