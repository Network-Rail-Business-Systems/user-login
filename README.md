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
    * Which User model to use locally for login
    * Which attribute identifies the user in model
    * Which attribute uniquely identifies the user in model
    */
    'local-model' => \App\Models\User::class,
    'local-model-identifier' => 'username',
    'local-unique-identifier' => 'guid',

    /*
     * The attribute used for authentication.
     * In the case of LDAP, this could be 'samaccountname'.
     */
    'auth-identifier' => 'samaccountname',

    /*
    * Which User model to use to get the unique identifier
    * Which unique attribute identifies the user in model
    */
    'sync-user' => [
        'model' => \LdapRecord\Models\ActiveDirectory\User::class,
        'unique-identifier' => 'objectguid',
    ],
    
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
In your User model or any other model you want to use for authentication, must implement the ```ExistingUser``` interface and use the ```ExistingUserUniqueIdentifier``` trait.

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

### Trait: ExistingUserUniqueIdentifier

The ```ExistingUserUniqueIdentifier``` trait provides a method ```uniqueIdentifier``` to fetch a user unique identifier (like a GUID) from either LDAP or your local database.

```
<?php

namespace NetworkRailBusinessSystems\UserLogin\Traits;

trait ExistingUserUniqueIdentifier
{
    // return The unique identifier for the user, or null if not found.
    public static function uniqueIdentifier(string $username): ?string
    {
        // Model for the LDAP or local database source (configurable)
        $ldapModel = config('user-login.sync-user.model');

        // Model for the local database (configurable)
        $eloquentModel = config('user-login.local-model');

        // Attribute that holds the unique identifier (e.g., GUID or objectguid, configurable)
        $guidAttribute = config('user-login.sync-user.unique-identifier');

        // Attribute used to authenticate the user (e.g., username or samaccountname, configurable)
        $authAttribute = config('user-login.auth-identifier');

        // Query the LDAP model (or local source) using the auth attribute
        $existingUser = $ldapModel::query()
            ->where($authAttribute, '=', $username)
            ->first();

        return is_a($existingUser, $eloquentModel) === true
            ? $existingUser?->getAttributeValue($guidAttribute)
            : $existingUser?->getAttributeValue($guidAttribute)[0];
    }
}
```

# Usage

The package handles user routing and validation without requiring a custom Login Controller and Login Request.

### How to Set Up the Login Functionality

To get the user login functionality up and running, follow these steps:

1. Implement the ```ExistingUser``` Interface and Use the Trait ```ExistingUserUniqueIdentifier``` In your User model or any model you're using for authentication.

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use NetworkRailBusinessSystems\UserLogin\Interfaces\ExistingUser;
use NetworkRailBusinessSystems\UserLogin\Traits\ExistingUserUniqueIdentifier;

class User extends Model implements ExistingUser
{
    // Use the ExistingUserUniqueIdentifier trait to retrieve the user's unique identifier.
    use ExistingUserUniqueIdentifier;

    // Other logic...
}
```

2. Add the following route macro to your routes/web.php file to handle the login routes automatically.

```
Route::userLogin();
```