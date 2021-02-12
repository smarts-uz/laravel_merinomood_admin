<?php

namespace Arniro\Admin;

use Illuminate\Support\Facades\Route;

class RouteRegistration
{
    protected $vendorNamespace = 'Arniro\Admin\Http\Controllers';

    protected function namespace()
    {
        return app()->getNamespace() . 'Http\Controllers\Admin';
    }

    protected function mapApiRoutes()
    {
        if (!file_exists(base_path('routes/admin.php'))) return $this;

        Route::middleware(config('admin.middleware'))
            ->namespace($this->namespace())
            ->prefix('admin/api')
            ->group(base_path('routes/admin.php'));

        return $this;
    }

    protected function mapVendorApiRoutes()
    {
        Route::middleware(config('admin.middleware'))
            ->namespace($this->vendorNamespace)
            ->prefix('admin/api')
            ->group(__DIR__ . '/../routes/api.php');

        return $this;
    }

    protected function mapFrontendRoutes()
    {
        Route::view('/{any}', 'admin::admin')
            ->where('any', 'admin/?.*')
            ->middleware(config('admin.middleware'));

        return $this;
    }

    public function withAuthenticationRoutes()
    {
        Route::prefix('admin')->namespace('Arniro\Admin\Http\Controllers\Auth')
            ->middleware('web')->group(function () {
                Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
                Route::post('login', 'LoginController@login');
                Route::post('logout', 'LoginController@logout')->name('admin.logout');
            });

        return $this;
    }

    public function register()
    {
        $this->mapApiRoutes()
            ->mapVendorApiRoutes()
            ->mapFrontendRoutes();
    }
}
