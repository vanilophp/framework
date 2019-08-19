<?php
/**
 * Contains the base TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */

namespace Vanilo\Order\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\Address\Providers\ModuleServiceProvider as KonektAddressModule;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Order\Providers\ModuleServiceProvider as OrderModule;
use Vanilo\Order\Tests\Dummies\Product;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Relation::morphMap([
            shorten(Product::class) => Product::class
        ]);

        $this->setUpDatabase($this->app);

        $this->app->concord->registerModel(
            AddressContract::class,
            \Vanilo\Order\Tests\Dummies\Address::class
        );
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
            LaravelMigrationCompatibilityProvider::class
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $engine = env('TEST_DB_ENGINE', 'sqlite');

        $app['config']->set('database.default', $engine);
        $app['config']->set('database.connections.' . $engine, [
            'driver'   => $engine,
            'database' => 'sqlite' == $engine ? ':memory:' : 'user_test',
            'prefix'   => '',
            'host'     => '127.0.0.1',
            'username' => env('TEST_DB_USERNAME', 'pgsql' === $engine ? 'postgres' : 'root'),
            'password' => env('TEST_DB_PASSWORD', ''),
        ]);

        if ('pgsql' === $engine) {
            $app['config']->set("database.connections.{$engine}.charset", 'utf8');
        }
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->artisan('migrate:reset');
        $this->loadLaravelMigrations();
        $this->artisan('migrate', ['--force' => true]);

        $app['db']->connection()->getSchemaBuilder()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->string('name');
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
            KonektAddressModule::class,
            OrderModule::class
        ]);
    }
}
