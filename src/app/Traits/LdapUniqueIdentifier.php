<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait LdapUniqueIdentifier
{
    public static function uniqueIdentifier(string $username): ?string
    {
        // Model for the LDAP database
        $ldapModel = config('user-login.ldap-user-model');

        // Attribute that holds the unique identifier (e.g., objectguid)
        $guidAttribute = config('user-login.ldap-unique-identifier');

        // Attribute used to authenticate the user (e.g., samaccountname)
        $authAttribute = config('user-login.auth-identifier');

        // Query the LDAP model and return unique identifier or null
        return $ldapModel::query()
            ->where($authAttribute, '=', $username)
            ->first()?->getAttributeValue($guidAttribute)[0];
    }
}
