<?php

namespace Arniro\Admin\Tests;

use Arniro\Admin\Tests\Fixtures\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

abstract class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/Factories');

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    protected function getPackageProviders($app)
    {
        return [
            'Arniro\Admin\Tests\TestServiceProvider',
            'Arniro\Admin\AdminCoreServiceProvider',
            'Arniro\Admin\AdminApplicationServiceProvider',
        ];
    }

    /**
     * Define environment.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = require(__DIR__ . '/../config/admin.php');

        $app['config']->set('admin.middleware', $config['middleware']);

        $app['config']->set('app.locales', [
            'ru' => 'Russian',
            'en' => 'English'
        ]);

        $app['config']->set('app.locale', 'ru');
    }
}
