<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

interface ExistingUser
{
    public static function mail(string $username): ?string;
}
