<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait DbUniqueIdentifier
{
    /**
     * This trait method retrieve the unique identifier (such as a GUID) for a user from the local database
     */
    public static function uniqueIdentifier(string $username): ?string
    {
        // Model for the local database
        $eloquentModel = config('user-login.local-model');

        // Attribute that holds the unique identifier (e.g., GUID)
        $guidAttribute = config('user-login.local-unique-identifier');

        // Attribute used to authenticate the user (e.g., username or samaccountname)
        $authAttribute = config('user-login.local-model-identifier');

        // Query the local model and return unique identifier or null
        return $eloquentModel::query()
            ->where($authAttribute, '=', $username)
            ->first()?->{$guidAttribute};
    }
}
