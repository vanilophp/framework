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
            ConcordServiceProvider::class
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
