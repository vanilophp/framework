<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Tests;

use Vanilo\Shipment\Contracts\Carrier as CarrierContract;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Contracts\ShippingCategory as ShippingCategoryContract;
use Vanilo\Shipment\Contracts\ShippingMethod as ShippingMethodContract;
use Vanilo\Shipment\Models\Carrier;
use Vanilo\Shipment\Models\Shipment;
use Vanilo\Shipment\Models\ShippingCategory;
use Vanilo\Shipment\Models\ShippingMethod;
use Vanilo\Shipment\Providers\ModuleServiceProvider;

class ModuleTest extends TestCase
{
    /** @test */
    public function module_loads()
    {
        $this->assertInstanceOf(
            ModuleServiceProvider::class,
            $this->app->concord->module('vanilo.shipment')
        );
    }

    /** @test */
    public function models_are_registered()
    {
        $models = $this->app->concord->getModelBindings();

        // Default model bindings should be registered by default
        $this->assertEquals(ShippingCategory::class, $models->get(ShippingCategoryContract::class));
        $this->assertEquals(Shipment::class, $models->get(ShipmentContract::class));
        $this->assertEquals(Carrier::class, $models->get(CarrierContract::class));
        $this->assertEquals(ShippingMethod::class, $models->get(ShippingMethodContract::class));
    }
}
