<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

interface ExistingUser
{
    public static function uniqueIdentifier(string $username): ?string;
}
