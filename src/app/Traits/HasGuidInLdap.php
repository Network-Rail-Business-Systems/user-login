<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait HasGuidInLdap
{
    public static function uniqueIdentifier(string $username): ?string
    {
        $ldapModel = config('user-login.ldap-user-model');

        $guidAttribute = config('user-login.ldap-unique-identifier');

        $authAttribute = config('user-login.ldap-model-identifier');

        return $ldapModel::query()
            ->select($guidAttribute)
            ->where($authAttribute, '=', $username)
            ->first()?->getAttributeValue($guidAttribute)[0];
    }
}
