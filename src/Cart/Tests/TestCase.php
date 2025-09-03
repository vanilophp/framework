<?php

declare(strict_types=1);

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

use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends TestCaseWithoutDB
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getEnvironmentSetUp($app)
    {
        //$app['path.lang'] = __DIR__ . '/lang';

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

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
}
