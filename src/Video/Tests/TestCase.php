<?php

declare(strict_types=1);

namespace Vanilo\Video\Tests;

use Illuminate\Foundation\Application;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Video\Providers\ModuleServiceProvider as VideoModule;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelMigrationCompatibilityProvider::class,
            ConcordServiceProvider::class
        ];
    }

    /**
     * Set up the environment.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): void
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
     * @param Application $app
     */
    protected function setUpDatabase(Application $app): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        \Artisan::call('migrate', ['--force' => true]);
    }

    /**
     * @inheritdoc
     */
    protected function resolveApplicationConfiguration($app): void
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']->set('concord.modules', [
            VideoModule::class,
        ]);
    }
}
