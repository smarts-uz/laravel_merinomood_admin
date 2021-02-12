<?php

namespace Arniro\Admin\Tests;

use Arniro\Admin\Parsers\ResourceParser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::middleware(config('admin.middleware'))
            ->namespace('Arniro\Admin\Tests\Fixtures')
            ->prefix('admin/api');

        $this->app->singleton(ResourceParser::class, function () {
            ResourceParser::setNamespace('Arniro\\Admin\\Tests\\Fixtures\\');

            return new ResourceParser;
        });

        $this->loadMigrationsFrom([
            '--database' => 'mysql',
            '--realpath' => realpath(__DIR__ . '/Migrations')
        ]);
    }
}
