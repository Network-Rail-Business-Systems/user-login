<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait HasGuidInDatabase
{
    /**
     * This method retrieve the unique identifier (such as a GUID) for a user from the local database
     */
    public static function uniqueIdentifier(string $username): ?string
    {
        $eloquentModel = config('user-login.local-model');

        $guidAttribute = config('user-login.local-unique-identifier');

        $authAttribute = config('user-login.local-model-identifier');

        return $eloquentModel::query()
            ->select($guidAttribute)
            ->where($authAttribute, '=', $username)
            ->first()?->$guidAttribute;
    }
}
