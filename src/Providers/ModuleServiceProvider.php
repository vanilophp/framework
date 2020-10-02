<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Product\Models\Product;
use Vanilo\Product\Models\ProductState;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Product::class
    ];

    protected $enums = [
        ProductState::class
    ];
}
