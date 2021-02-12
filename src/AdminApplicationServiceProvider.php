<?php

namespace Arniro\Admin;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminApplicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes();

        Admin::serving(function () {
            $this->authorize();

            Admin::ui()->initialize();
        });
    }

    protected function routes()
    {
        Admin::routes()
            ->withAuthenticationRoutes()
            ->register();
    }

    protected function authorize()
    {
        $this->gate();

        Admin::auth(function () {
            return app()->environment('local') ||
                Gate::check('viewAdmin', [auth()->user()]);
        });
    }

    protected function gate()
    {
        Gate::define('viewAdmin', function ($user) {
            return true;
        });
    }

    public function register()
    {
        //
    }
}
