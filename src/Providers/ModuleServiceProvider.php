<?php
/**
 * Contains the Order module's ServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */


namespace Vanilo\Order\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Models\OrderItem;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Order::class,
        OrderItem::class
    ];
}

