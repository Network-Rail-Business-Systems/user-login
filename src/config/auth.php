<?php

use NetworkRailBusinessSystems\UserLogin\Models\User;
use LdapRecord\Models\DirectoryServer\User as LdapUser;


return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'ldap',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ],

        'ldap' => [
            'driver' => 'ldap',
            'model' => LdapUser::class,
            'database' => [
                'model' => User::class,
                'sync_passwords' => false,
                'sync_attributes' => [
                    'first_name' => 'givenname',
                    'last_name' => 'sn',
                    'email' => 'mail',
                    'username' => 'samaccountname',
                ],
                'sync_existing' => [
                    'username' => 'samaccountname',
                ],
            ],
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
