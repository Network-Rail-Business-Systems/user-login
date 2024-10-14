<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

use LdapRecord\Models\ActiveDirectory\User as LdapUser;

trait GetExistingUser
{
    /**
     * @codeCoverageIgnore
     */
    public static function mail($username): ?string
    {
        return LdapUser::query()
            ->where('samaccountname', '=', $username)
            ->first()?->getAttributeValue('mail')[0];
    }
}
