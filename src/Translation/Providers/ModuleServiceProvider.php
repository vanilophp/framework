<?php

declare(strict_types=1);

namespace Vanilo\Translation\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Translation\Models\Translation;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Translation::class,
    ];
}
