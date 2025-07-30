<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Tests;

use Konekt\Concord\ConcordServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Vanilo\Foundation\Providers\ModuleServiceProvider as VaniloModule;

abstract class TestCaseWithoutDB extends Orchestra
{

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
            VaniloModule::class
        ]);
    }
}
