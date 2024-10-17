<?php

namespace NetworkRailBusinessSystems\UserLogin\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth\LoginController;

class UserLoginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/user-login.php',
            'user-login'
        );
    }

    public function boot(): void
    {
        $this->bootPublishes();
        $this->bootRoutes();
        $this->bootViews();
    }

    protected function bootPublishes(): void
    {
        $currentTimeStamp = date('Y_m_d_His');

        $this->publishes([
            __DIR__.'/../../config/user-login.php' => config_path('user-login.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/user-login'),
        ], 'views');
    }

    protected function bootRoutes(): void
    {
        Route::macro('userLogin', function () {
            Route::prefix('login')
                ->controller(LoginController::class)
                ->group(function () {
                    Route::middleware('guest')->group(function () {
                        Route::get('/', 'index')->name('login');
                        Route::post('/', 'signIn')->name('sign-in');
                    });

                    Route::middleware('auth')->group(function () {
                        Route::get('/sign-out', 'signOut')->name('sign-out');
                    });
                });
        });
    }

    protected function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'user-login');
    }
}
