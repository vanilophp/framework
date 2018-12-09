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

namespace Vanilo\Properties\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Property::class,
        PropertyValue::class
    ];
}
