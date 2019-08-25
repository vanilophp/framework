<?php
/**
 * Contains the base TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-28
 *
 */

namespace Vanilo\Cart\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Vanilo\Cart\Providers\ModuleServiceProvider as CartModule;
use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\User;

abstract class TestCase extends Orchestra
{
    use Laravel54TestCompatibility;

    protected function setUp(): void
    {
        parent::setUp();

        // The cart module is unaware of any actual Buyables,
        // so the mapping gets defined here. Any consumers
        // of this module need to add their mapping too
        Relation::morphMap([
            shorten(Product::class) => Product::class
        ]);

        $this->withFactories(realpath(__DIR__ . '/factories'));
        $this->setUpDatabase($this->app);
        $this->startSession();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
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

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        //$app['path.lang'] = __DIR__ . '/lang';

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
        \Artisan::call('migrate', ['--force' => true]);

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * @inheritdoc
     */
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
