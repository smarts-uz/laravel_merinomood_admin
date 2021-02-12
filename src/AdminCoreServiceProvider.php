<?php

namespace Arniro\Admin;

use Arniro\Admin\Console\CreateUserCommand;
use Arniro\Admin\Console\InstallCommand;
use Arniro\Admin\Console\PublishCommand;
use Arniro\Admin\Console\ResourceCommand;
use Illuminate\Support\ServiceProvider;

class AdminCoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(base_path('admin/resources/views'), 'admin');
        $this->loadViewsFrom(base_path('admin/vendor/views'), 'admin');

        $this->loadTranslationsFrom(base_path('admin/resources/lang'), 'admin');
        $this->loadTranslationsFrom(base_path('admin/vendor/lang'), 'admin');

        $this->registerPublishing();
    }

    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../resources/js' => base_path('admin/vendor/js'),
            __DIR__ . '/../resources/sass' => base_path('admin/vendor/sass'),
            __DIR__ . '/../resources/views' => base_path('admin/vendor/views'),
            __DIR__ . '/../resources/lang' => base_path('admin/vendor/lang'),
            __DIR__ . '/../resources/fonts' => public_path('vendor/admin/fonts'),
            __DIR__ . '/../resources/images' => public_path('vendor/admin/images'),
        ], 'admin-resources');

        $this->publishes([
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
            __DIR__ . '/../tailwind.js' => base_path('admin/tailwind.js'),
            __DIR__ . '/../package.json' => base_path('admin/package.json'),
            __DIR__ . '/../webpack.mix.js' => base_path('admin/webpack.mix.js'),
        ], 'admin-config');

        $this->publishes([
            __DIR__ . '/../src/Media/2019_11_07_000000_create_media_table.php' =>
                database_path('migrations/2019_11_07_000000_create_media_table.php'),
        ], 'admin-media');
    }

    public function register()
    {
        $this->commands([
            PublishCommand::class,
            InstallCommand::class,
            ResourceCommand::class,
            CreateUserCommand::class,
        ]);

        $this->app->bind('resource', 'Arniro\Admin\Parsers\ResourceParser');
    }
}
