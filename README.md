 # User Login.
This package provides login functionality for authenticating users with Active Directory accounts.

Built for [Laravel 11](https://laravel.com/).

## Installation

Add the library using Composer:
   ```
   composer require networkrailbusinesssystems/user-login
   ```
The service provider will be automatically registered.

## Configuration

1. By default, this package uses the GOV.UK login page. If your system does not use the GOV.UK Design, you can publish the configuration file using Artisan:
   ```
   php artisan vendor:publish --provider="NetworkRailBusinessSystems\UserLogin\UserLoginServiceProvider"
   ```
   After publishing, update the user-login config to point to the custom login view:
   ```php
   return [
        'view' => 'login',
   ];
   ```
2. You can optionally publish the user migration with:
   ```
   php artisan vendor:publish --provider="NetworkRailBusinessSystems\UserLogin\UserLoginServiceProvider" --tag="uesr-login-migrations"
   ```
## Usage
This library takes care of the login functionality, so there’s no need to create a controller or handle authentication manually.