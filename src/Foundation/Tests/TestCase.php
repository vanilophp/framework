<?php

declare(strict_types=1);

/**
 * Contains the TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-09
 *
 */

namespace Vanilo\Foundation\Tests;

use Cviebrock\EloquentSluggable\ServiceProvider as SluggableServiceProvider;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Konekt\Search\Providers\SearchServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Vanilo\Foundation\Providers\ModuleServiceProvider as VaniloModule;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class,
            MediaLibraryServiceProvider::class,
            LaravelMigrationCompatibilityProvider::class,
            SluggableServiceProvider::class,
            SearchServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $engine = env('TEST_DB_ENGINE', 'sqlite');
        $app['path.lang'] = __DIR__ . '/lang';
        $app['config']->set('database.default', $engine);
        $app['config']->set('database.connections.' . $engine, [
            'driver' => $engine,
            'database' => 'sqlite' == $engine ? ':memory:' : 'vanilo_test',
            'prefix' => '',
            'host' => env('TEST_DB_HOST', '127.0.0.1'),
            'username' => env('TEST_DB_USERNAME', 'pgsql' === $engine ? 'postgres' : 'root'),
            'password' => env('TEST_DB_PASSWORD', ''),
            'port' => env('TEST_DB_PORT'),
        ]);

        if ('pgsql' === $engine) {
            $app['config']->set("database.connections.{$engine}.charset", 'utf8');
        }
    }

    protected function setUpDatabase($app)
    {
        $this->loadLaravelMigrations();
        $this->artisan('migrate', ['--force' => true]);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('concord.modules', [
            VaniloModule::class
        ]);
    }
}
