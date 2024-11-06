<?php

namespace NetworkRailBusinessSystems\UserLogin\Helpers;

use ErrorException;
use Illuminate\Support\Collection;
use LdapRecord\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class LdapHelper
{
    public static function searchByEmail(string $term, int $limit = 5, array $select = []): Collection
    {
        $ldapModel = config('user-login.ldap-user-model');

        return $ldapModel::query()
            ->where('mail', 'starts_with', $term)
            ->andFilter(function (Builder $query) {
                $query->whereHas('givenname')->whereHas('sn'); // @codeCoverageIgnore
            })
            ->select(array_merge(['givenname', 'sn', 'mail'], $select))
            ->limit($limit)
            ->get();
    }

    public static function searchByName(string $term, int $limit = 5, array $select = []): Collection
    {
        $ldapModel = config('user-login.ldap-user-model');

        return $ldapModel::query()
            ->where('givenname', 'starts_with', $term)
            ->orWhere('sn', 'starts_with', $term)
            ->andFilter(function (Builder $query) {
                $query->whereHas('givenname')->whereHas('sn'); // @codeCoverageIgnore
            })
            ->select(array_merge(['givenname', 'sn', 'mail'], $select))
            ->limit($limit)
            ->get();
    }

    public static function import(string $email): Model
    {
        $localModel = config('user-login.local-model');
        $emailKey = config('user-login.local-email-identifier');

        if (self::searchByEmail($email)->isEmpty() !== false) {
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
            '--filter' => "(mail=$email)",
        ]);

        try {
            return $localModel::query()
                ->where($emailKey, '=', $email)
                ->firstOrFail();
        } catch (Throwable $exception) {
            throw new ErrorException(
                "Import failed; no User was found with the e-mail \"$email\" in this system",
            );
        }
    }
}
