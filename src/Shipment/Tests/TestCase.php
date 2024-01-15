<?php

declare(strict_types=1);

/**
 * Contains the base TestCase class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-16
 *
 */

namespace Vanilo\Shipment\Tests;

use Konekt\Address\Contracts\Address;
use Konekt\Address\Providers\ModuleServiceProvider as AddressModule;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Shipment\Providers\ModuleServiceProvider as ShipmentModule;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        concord()->registerModel(Address::class, Dummies\Address::class);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelMigrationCompatibilityProvider::class,
            ConcordServiceProvider::class
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        \Artisan::call('migrate', ['--force' => true]);
    }

    /**
     * @inheritdoc
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            AddressModule::class,
            ShipmentModule::class
        ]);
    }
}
