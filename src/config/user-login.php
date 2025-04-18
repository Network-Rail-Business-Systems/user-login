<?php

return [
    /*
     * This specifies the login view to be used.
     * Change to 'login' for custom login view.
     */
    'view' => 'gov-uk-login',

    /*
    * Which attribute authenticate the user
    * In the case of LDAP, this could be 'samaccountname'.
    */
    'auth-identifier' => 'samaccountname',

    /*
    * Which User model to use locally for login
    * Which attribute identifies the user in model
    * Which attribute uniquely identifies the user in model
    */
    'local-model' => \App\Models\User::class,
    'local-model-identifier' => 'username',
    'local-unique-identifier' => 'guid',
    'local-email-identifier' => 'email',

    /*
    * Which LdapRecord User model to use to get the unique identifier
    * Which attribute identifies the user in LDAP
    * Which unique attribute identifies the user in LDAP
    */
    'ldap-user-model' => \LdapRecord\Models\ActiveDirectory\User::class,
    'ldap-model-identifier' => 'samaccountname',
    'ldap-unique-identifier' => 'objectguid',

    /*
    * Custom messages for login success or failure.
    */
    'login-failed-message' => 'Sign-in failed; check your details and try again',

    'login-success-message' => 'You have successfully signed in',

    /*
    * Configuration for the "Forgot Password" section in view.
    * You can set a description and routes for IT helpdesk or password reset.
    */
    'forgot-password' => [
        'description' => null,
        'routes' => [
            'label' => 'name',
        ],
    ],
];
