<?php

declare(strict_types=1);

namespace Vanilo\Video\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Video\Models\Video;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Video::class,
    ];
}
