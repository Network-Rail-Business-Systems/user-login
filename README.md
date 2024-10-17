# User Login.
This package provides user login functionality.

Built for [Laravel 11](https://laravel.com/).

## Installation

Add the library using Composer:
```
composer require networkrailbusinesssystems/user-login
```
The service provider will be automatically registered.

## Configuration

#### 1. Export the config file
To customize the package, export the configuration file using Artisan:

```
php artisan vendor:publish --provider="NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider" --tag=config
```

#### 2. Publish the views.
To modify the login views, publish the package views using Artisan:

```
php artisan vendor:publish --provider="NetworkRailBusinessSystems\UserLogin\Providers\UserLoginServiceProvider" --tag=views
```

#### 3. Available Configuration Options
After publishing, you can modify the following configuration options in the config/user-login.php file:

```
return [
    /*
     * The view to be used for the login page.
     * Change to 'login' for custom login view.
     */
    'view' => 'gov-uk-login',

    /*
     * The attribute used for authentication.
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
    'login-failed-message' => 'Sign-in failed; check your details and try again.',
    'login-success-message' => 'You have successfully signed in.',

    /*
     * Configuration for the "Forgot Password" section in view.
     * You can set a description and routes for IT helpdesk or password reset.
     */
    'forgot_password' => [
        'description' => null,
        'routes' => [
            'label' => 'name',
        ],
    ],
];
```

## Routing

A route macro is provided to handle the login.

```
Route::userLogin();
```

## interface: ExistingUser

The `ExistingUser` interface defines an abstract function for retrieving the unique identifier:

```
<?php

namespace NetworkRailBusinessSystems\UserLogin\Interfaces;

interface ExistingUser
{
    /*
     * Retrieve the unique identifier for a user by their auth identifier (e.g., username or samaccountname).
     * This method should return a unique identifier associated with the user, or null if the user does not exist.
     */
    public static function uniqueIdentifier(string $username): ?string;
}
```

### Trait: DbUniqueIdentifier

The ```DbUniqueIdentifier``` trait provides a method ```uniqueIdentifier``` to fetch a user unique identifier (like a GUID) from your local database.

```
<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait ExistingUserUniqueIdentifier
{
    // return The unique identifier for the user, or null if not found.
    public static function uniqueIdentifier(string $username): ?string
    {
        // Model for the local database
        $eloquentModel = config('user-login.local-model');

        // Attribute that holds the unique identifier (e.g., GUID)
        $guidAttribute = config('user-login.local-unique-identifier');

        // Attribute used to authenticate the user (e.g., username or samaccountname)
        $authAttribute = config('user-login.local-model-identifier');

        // Query the local model and return unique identifier or null
        return $eloquentModel::query()
                ->where($authAttribute, '=', $username)
                ->first()?->{$guidAttribute};
    }
}
```

### Trait: LdapUniqueIdentifier

The  ```LdapUniqueIdentifier``` trait provides a method ```uniqueIdentifier``` to fetch a user unique identifier (like a objectguid) from LDAP.

```
<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait LdapUniqueIdentifier
{
    public static function uniqueIdentifier(string $username): ?string
    {
        // Model for the LDAP database
        $ldapModel = config('user-login.ldap-user-model');

        // Attribute that holds the unique identifier (e.g., objectguid)
        $guidAttribute = config('user-login.ldap-unique-identifier');

        // Attribute used to authenticate the user (e.g., samaccountname)
        $authAttribute = config('user-login.ldap-model-identifier');

        // Query the LDAP model and return unique identifier or null
        return $ldapModel::query()
                ->where($authAttribute, '=', $username)
                ->first()?->getAttributeValue($guidAttribute)[0];
    }
}
```

# Usage

The package handles user routing and validation without requiring a custom Login Controller and Login Request.

### How to Set Up the Login Functionality

To get the user login functionality up and running, follow these steps:

1. In your User model or any other model you want to use for authentication, must implement the ```ExistingUser``` interface and use one of the provided traits (LdapUniqueIdentifier, DbUniqueIdentifier) to retrieve the unique identifier.

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use NetworkRailBusinessSystems\UserLogin\Interfaces\ExistingUser;
use NetworkRailBusinessSystems\UserLogin\Traits\ExistingUserUniqueIdentifier;

class User extends Model implements ExistingUser
{
    use LdapUniqueIdentifier;

    // Other logic...
}
```

2. Add the following route macro to your routes/web.php file to handle the login routes automatically.

```
Route::userLogin();
```