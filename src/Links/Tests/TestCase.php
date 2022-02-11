<?php

declare(strict_types=1);

/**
 * Contains the TestCase class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Tests;

use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Links\Providers\ModuleServiceProvider as LinksModule;

class TestCase extends Orchestra
{
    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = __DIR__ . '/lang';

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
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
            LinksModule::class
        ]);
    }

    protected function setUpDatabase($app)
    {
        \Artisan::call('migrate', ['--force' => true]);
    }
}
