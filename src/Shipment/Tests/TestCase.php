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

abstract class TestCase extends TestCaseWithoutDB
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        concord()->registerModel(Address::class, Dummies\Address::class);
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
}
