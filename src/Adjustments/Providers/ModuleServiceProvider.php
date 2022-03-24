<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Adjustments\Models\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentType;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Adjustment::class
    ];

    protected $enums = [
        AdjustmentType::class
    ];
}
