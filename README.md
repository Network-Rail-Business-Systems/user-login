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

| Option                  | Type   | Default                                              | Usage                                                                                                                         |
|-------------------------|--------|------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------|
| view                    | string | gov-uk-login                                         | Customise the view to be used for the login page                                                                              |
| auth-identifier         | string | samaccountname                                       | Customise the attribute used for authentication                                                                               |
| local-email-indentifer  | string | email                                                | Customise the email attribute for the user in local model                                                                     |
| local-model             | class  | \App\Models\User::class                              | Customise the class for local user                                                                                            |
| local-model-identifier  | string | username                                             | Customise the attribute identifies the user in local model                                                                    |
| local-unique-identifier | string | guid                                                 | Customise the unique attribute identifies the user in local model                                                             |
| ldap-model              | class  | \LdapRecord\Models\ActiveDirectory\User::class       | Customise the class for LDAP user                                                                                             |
| ldap-model-identifier   | string | samaccountname                                       | Customise the attribute identifies the user in LDAP model                                                                     |
| ldap-unique-identifier  | string | objectguid                                           | Customise the unique attribute identifies the user in LDAP model                                                              |
| login-failed-message    | string | Sign-in failed; check your details and try again.    | Customise the message for login failure                                                                                       |
| login-success-message   | string | You have successfully signed in.                     | Customise the message for login success                                                                                       |
| forgot-password         | array  | 'description' => null, routes => ['label' => 'name'] | Customise the "Forgot Password" section in view  <br/>You can set a description and routes for IT helpdesk or password reset. |


## Routing

A route macro is provided to handle the login.

```
Route::userLogin();
```

## interface: ExistingUser

The `ExistingUser` interface defines an abstract function for retrieving the unique identifier

## Trait: HasGuidInDatabase

The `HasGuidInDatabase` trait provides a method `uniqueIdentifier` to fetch a user unique identifier (like a GUID) from your local database.

## Trait: HasGuidInLdap

The  `HasGuidInLdap` trait provides a method `uniqueIdentifier` to fetch a user unique identifier (like a objectguid) from LDAP.

## uniqueIdentifier($username)

- $username: This is the value used to authenticate the user.

- The method returns the user GUID if found, or null if no match is found.

## Usage

The package handles user routing and validation without requiring a custom Login Controller and Login Request.

### How to Set Up the Login Functionality

To get the user login functionality up and running, follow these steps:

1. In your User model or any other model you want to use for authentication, must implement the `ExistingUser` interface and use one of the provided traits (HasGuidInLdap, HasGuidInDatabase) to retrieve the unique identifier.

```
class User extends Model implements ExistingUser
{
    use HasGuidInLdap;

    // Other logic...
}
```

2. Add the following route macro to your routes/web.php file to handle the login routes automatically.

```
Route::userLogin();
```
