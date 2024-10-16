<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait ExistingUserUniqueIdentifier
{
    public static function uniqueIdentifier(string $username): ?string
    {
        $ldapModel = config('user-login.sync-user.model');

        $eloquentModel = config('user-login.model');

        $guidAttribute = config('user-login.sync-user.unique-identifier');

        $authAttribute = config('user-login.auth-identifier');

        $existingUser = $ldapModel::query()
            ->where($authAttribute, '=', $username)
            ->first();

        return is_a($existingUser, $eloquentModel) === true
            ? $existingUser?->getAttributeValue($guidAttribute)
            : $existingUser?->getAttributeValue($guidAttribute)[0];
    }
}
