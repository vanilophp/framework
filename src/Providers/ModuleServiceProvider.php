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
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Factories\OrderFactory;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Models\OrderItem;
use Vanilo\Order\Models\OrderStatus;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Order::class,
        OrderItem::class
    ];

    protected $enums = [
        OrderStatus::class
    ];

    public function boot()
    {
        parent::boot();

        // Bind the default implementation to the interface
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);
    }
}


