<?php

// Set the default view to use the gov.uk template
// Other UIs can modify the login blade as needed
return [
    'view' => 'gov-uk-login',
    'model' => '\App\Models\User',
    'ldap-sync' => false,

    'forgot_password' => [
        'body-text' => null, // Assuming you want the URL generated from the route
        'password-reset-route' => null,
        'it-helpdesk-route' => null,
    ],
];
