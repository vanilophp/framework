<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-17
 *
 */

namespace Vanilo\Taxes\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Taxes\Models\TaxCategory;
use Vanilo\Taxes\Models\TaxRate;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        TaxCategory::class,
        TaxRate::class,
    ];
}
