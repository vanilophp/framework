<?php

declare(strict_types=1);

/**
 * Contains the base TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */

namespace Vanilo\Order\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Artisan;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\Address\Providers\ModuleServiceProvider as KonektAddressModule;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Konekt\User\Providers\ModuleServiceProvider as KonektUserModule;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Order\Providers\ModuleServiceProvider as OrderModule;
use Vanilo\Order\Tests\Dummies\Product;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Relation::morphMap([
            shorten(Product::class) => Product::class
        ]);

        $this->app->concord->registerModel(
            AddressContract::class,
            Dummies\Address::class
        );

        $this->withFactories(__DIR__ . '/factories');
        $this->setUpDatabase($this->app);
    }

    protected function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = [
            ConcordServiceProvider::class,
            LaravelMigrationCompatibilityProvider::class
        ];

        if (version_compare($app->version(), '5.6.0', '<')) {
            $providers[] = \Orchestra\Database\ConsoleServiceProvider::class;
        }

        return $providers;
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $engine = env('TEST_DB_ENGINE', 'sqlite');

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
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            KonektUserModule::class,
            KonektAddressModule::class,
            OrderModule::class
        ]);
    }
}
