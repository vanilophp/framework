<?php

declare(strict_types=1);

/**
 * Contains the base TestCase class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-17
 *
 */

namespace Vanilo\Taxes\Tests;

use Konekt\Address\Contracts\Address;
use Konekt\Address\Providers\ModuleServiceProvider as AddressModule;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Adjustments\Providers\ModuleServiceProvider as AdjustmentsModule;
use Vanilo\Taxes\Providers\ModuleServiceProvider as TaxesModule;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        concord()->registerModel(Address::class, Dummies\Address::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelMigrationCompatibilityProvider::class,
            ConcordServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase($app)
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        \Artisan::call('migrate', ['--force' => true]);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            AddressModule::class,
            AdjustmentsModule::class,
            TaxesModule::class,
        ]);
    }
}
