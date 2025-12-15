<?php

declare(strict_types=1);

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

use Illuminate\Support\Str;
use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Order\Factories\OrderFactory;
use Vanilo\Order\Models\Billpayer;
use Vanilo\Order\Models\FulfillmentStatus;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Models\OrderItem;
use Vanilo\Order\Models\OrderStatus;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Billpayer::class,
        Order::class,
        OrderItem::class,
    ];

    protected $enums = [
        OrderStatus::class,
        FulfillmentStatus::class,
    ];

    public function boot()
    {
        parent::boot();

        $this->registerOrderNumberGenerator();

        // Bind the default implementation to the interface
        $this->app->bind(OrderFactoryContract::class, OrderFactory::class);
    }

    protected function registerOrderNumberGenerator()
    {
        $nsRoot = $this->getNamespaceRoot();

        $this->app->bind(OrderNumberGenerator::class, function ($app) use ($nsRoot) {
            $generatorClass = $app['config']->get('vanilo.order.number.generator', 'time_hash');
            if (!class_exists($generatorClass)) {
                $generatorClass = sprintf(
                    '%s\\Generators\\%sGenerator',
                    $nsRoot,
                    Str::studly($generatorClass)
                );
            }

            return $app->make($generatorClass);
        });
    }
}
