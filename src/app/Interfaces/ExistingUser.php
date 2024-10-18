<?php

namespace NetworkRailBusinessSystems\UserLogin\Interfaces;

interface ExistingUser
{
    /*
     * Retrieve the unique identifier for a user by their auth identifier (e.g., username or samaccountname)
     * This method should return a unique identifier associated with the user, or null if the user does not exist.
     */
    public static function uniqueIdentifier(string $username): ?string;
}
