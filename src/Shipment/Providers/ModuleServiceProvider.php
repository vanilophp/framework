<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-16
 *
 */

namespace Vanilo\Shipment\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Shipment\Models\Carrier;
use Vanilo\Shipment\Models\Shipment;
use Vanilo\Shipment\Models\ShipmentStatus;
use Vanilo\Shipment\Models\ShippingCategory;
use Vanilo\Shipment\Models\ShippingCategoryMatchingCondition;
use Vanilo\Shipment\Models\ShippingMethod;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Shipment::class,
        ShippingMethod::class,
        Carrier::class,
        ShippingCategory::class,
    ];

    protected $enums = [
        ShipmentStatus::class,
        ShippingCategoryMatchingCondition::class,
    ];
}
