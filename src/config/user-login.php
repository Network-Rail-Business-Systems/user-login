<?php

return [
    /*
     * Set the default view to use the gov.uk template, Change login for bulma
     */
    'view' => 'gov-uk-login',

    /*
    * Which User model to use locally for login
    * Which attribute identifies the user in model
    */
    'model' => \App\Models\User::class,
    'model-identifier' => 'username',

    /*
    * Messages for success or failed login
    */
    'login-failed-message' => 'Sign-in failed; check your details and try again',

    'login-success-message' => 'You have successfully signed in',

    /*
    * Which attribute authenticate the user
    */
    'auth-identifier' => 'samaccountname',

    /*
    * Which User model to use to get the unique identifier
    * Which attribute identifies the user in LDAP
    */
    'sync-user' => [
        'model' => \LdapRecord\Models\ActiveDirectory\User::class,
        'unique-identifier' => 'objectguid',
    ],

    'forgot_password' => [
        'description' => null,
        'routes' => [
            'label' => 'name',
        ],
    ],
];
