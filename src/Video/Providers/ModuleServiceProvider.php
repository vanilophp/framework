<?php

declare(strict_types=1);

namespace Vanilo\Video\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Video\Drivers\Youtube;
use Vanilo\Video\Models\Video;
use Vanilo\Video\VideoDrivers;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Video::class,
    ];

    public function boot(): void
    {
        parent::boot();

        VideoDrivers::register(Youtube::ID, Youtube::class);
    }
}
