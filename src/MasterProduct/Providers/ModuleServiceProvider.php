<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductVariant;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        MasterProduct::class,
        MasterProductVariant::class,
    ];
}
