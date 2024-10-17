<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait ExistingUserUniqueIdentifier
{
    public static function uniqueIdentifier(string $username): ?string
    {
        // Model for the LDAP or local database source (configurable)
        $ldapModel = config('user-login.sync-user.model');

        // Model for the local database (configurable)
        $eloquentModel = config('user-login.local-model');

        // Attribute that holds the unique identifier (e.g., GUID or objectguid, configurable)
        $guidAttribute = config('user-login.sync-user.unique-identifier');

        // Attribute used to authenticate the user (e.g., username or samaccountname, configurable)
        $authAttribute = config('user-login.auth-identifier');

        // Query the LDAP model (or local source) using the auth attribute
        $existingUser = $ldapModel::query()
            ->where($authAttribute, '=', $username)
            ->first();

        // Return unique identifier or null
        return is_a($existingUser, $eloquentModel) === true
            ? $existingUser?->getAttributeValue($guidAttribute)
            : $existingUser?->getAttributeValue($guidAttribute)[0];
    }
}
