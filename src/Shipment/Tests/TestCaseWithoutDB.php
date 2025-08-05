<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Tests;

use Konekt\Address\Providers\ModuleServiceProvider as AddressModule;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Shipment\Providers\ModuleServiceProvider as ShipmentModule;

abstract class TestCaseWithoutDB extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LaravelMigrationCompatibilityProvider::class,
            ConcordServiceProvider::class
        ];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            AddressModule::class,
            ShipmentModule::class
        ]);
    }
}
