<?php

// Set the default view to use the gov.uk template
// Other UIs can modify the login blade as needed
return [
    'view' => 'gov-uk-login',

    'model' => '\App\Models\User',

    'ldap-sync' => false,

    'forgot_password' => [
        'description' => null,
        'routes' => [
            'label' => 'name',
        ],
    ],
];
