<?php

declare(strict_types=1);

namespace Vanilo\Order\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\Address\Providers\ModuleServiceProvider as KonektAddressModule;
use Konekt\User\Providers\ModuleServiceProvider as KonektUserModule;
use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Order\Providers\ModuleServiceProvider as OrderModule;
use Vanilo\Order\Tests\Dummies\Product;

abstract class TestCaseWithoutDB extends Orchestra
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
    }


    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class,
        ];
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
