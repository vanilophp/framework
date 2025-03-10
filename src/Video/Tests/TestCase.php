<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests;

use Illuminate\Foundation\Application;
use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Video\Providers\ModuleServiceProvider as VideoModule;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders(Application $app): array
    {
        return [
            ConcordServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp(Application $app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase(Application $app): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        \Artisan::call('migrate', ['--force' => true]);
    }

    protected function resolveApplicationConfiguration(Application $app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            VideoModule::class,
        ]);
    }
}
