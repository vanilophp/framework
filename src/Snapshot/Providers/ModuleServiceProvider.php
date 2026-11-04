<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Snapshot\Models\Snapshot;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Snapshot::class,
    ];
}
