<?php

declare(strict_types=1);

namespace Vanilo\Cart\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Cart\Providers\ModuleServiceProvider as CartModule;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\User;

abstract class TestCaseWithoutDB extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // The cart module is unaware of any actual Buyables,
        // so the mapping gets defined here. Any consumers
        // of this module need to add their mapping too
        Relation::morphMap([
            shorten(Product::class) => Product::class
        ]);

        $this->startSession();
    }

    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Cart' => 'Vanilo\Cart\Facades\Cart'
        ];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            CartModule::class
        ]);

        $app['config']->set('session.drive', 'array');

        // Use the dummy user class
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
