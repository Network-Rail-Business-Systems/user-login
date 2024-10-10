<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait SyncWithLdap
{
    abstract public static function syncUser(string $username): bool;
}
