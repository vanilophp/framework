<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Promotion\Models\Coupon;
use Vanilo\Promotion\Models\Promotion;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Promotion::class,
        Coupon::class,
    ];
}
