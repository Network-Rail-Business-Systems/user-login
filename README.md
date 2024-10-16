# User Login.
This package provide user logging in functionality with authentication.

Built for [Laravel 11](https://laravel.com/).

## Installation

Add the library using Composer:
```
composer require networkrailbusinesssystems/user-login
```
The service provider will be automatically registered.

Export the config file using Artisan:
```
php artisan vendor:publish --provider="NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider" --tag=config
```

## Configuration

1. Publish the views using Artisan:

```
php artisan vendor:publish --provider="NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider" --tag=views
```

There are a few options that need to be configured before using the library.
```
return [
    // By default, the 'view' is set to 'gov-uk-login'. Change this to 'login' to point to your custom login view.
    'view' => 'gov-uk-login',

    // This specifies the model used to handle login.
    'model' => '\App\Models\User',

    // Set 'ldap-sync' to true if you are using LDAP for login authentication.
    'ldap-sync' => false,
    
    'forgot_password' => [
        // Description set the body text for the forgot password section.
        'description' => null,
        
        // Specify the routes for the password reset page or IT helpdesk, if needed.
        'routes' => [
            'label' => 'name',
        ]
    ]
];
```

## Routing

A route macro is provided to handle the login. Add the following to your routes/web.php file:

```
Route::userLogin();
```

## ExistingUser

This package provides an interface **ExistingUser** and a trait **GetExistingUser** to help you retrieve existing user emails from your LDAP model.

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\GetExistingUser;
use App\Traits\ExistingUser;

class User extends Model implements GetExistingUser
{
    use ExistingUser;

    ...................
}
```