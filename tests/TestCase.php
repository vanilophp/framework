<?php
/**
 * Contains the TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-09
 *
 */

namespace Vanilo\Framework\Tests;

use Cviebrock\EloquentSluggable\ServiceProvider as SluggableServiceProvider;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Database\Schema\Blueprint;
use Konekt\AppShell\Providers\ModuleServiceProvider as AppShellModule;
use Konekt\Gears\Providers\GearsServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Konekt\Menu\Facades\Menu;
use Konekt\Menu\MenuServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Vanilo\Framework\Providers\ModuleServiceProvider as VaniloModule;
use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(realpath(__DIR__ . '/factories'));
        $this->setUpDatabase($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class,
            MediaLibraryServiceProvider::class,
            GearsServiceProvider::class,
            LaravelMigrationCompatibilityProvider::class,
            BreadcrumbsServiceProvider::class,
            MenuServiceProvider::class,
            SluggableServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Breadcrumbs' => Breadcrumbs::class,
            'Menu'        => Menu::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = __DIR__ . '/lang';

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
        });

        \Artisan::call('migrate', ['--force' => true]);
    }

    /**
     * @inheritdoc
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('concord.modules', [
            AppShellModule::class,
            VaniloModule::class
        ]);
    }
}
