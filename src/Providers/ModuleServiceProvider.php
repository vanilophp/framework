<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Attributes\Models\Attribute;
use Vanilo\Attributes\Models\AttributeValue;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Attribute::class,
        AttributeValue::class
    ];
}
