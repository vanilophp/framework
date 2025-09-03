<?php

declare(strict_types=1);

namespace Vanilo\Category\Tests;

use Cviebrock\EloquentSluggable\ServiceProvider as SluggableServiceProvider;
use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Category\Providers\ModuleServiceProvider as CategoryModule;

abstract class TestCaseWithoutDB extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class,
            SluggableServiceProvider::class
        ];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('concord.modules', [
            CategoryModule::class
        ]);
    }
}
