<?php

declare(strict_types=1);

/**
 * Contains the ShipmentStatus class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-16
 *
 */

namespace Vanilo\Shipment\Models;

use Konekt\Enum\Enum;
use Vanilo\Shipment\Contracts\ShipmentStatus as ShipmentStatusContract;

class ShipmentStatus extends Enum implements ShipmentStatusContract
{
    public const __DEFAULT = self::NEW;

    public const NEW = 'new';
    public const INFO = 'info';
    public const READY = 'ready';
    public const CANCELLED = 'cancelled';
    public const PICKED_UP = 'picked_up';
    public const HUB_SCAN = 'hub_scan';
    public const OUT_FOR_DELIVERY = 'out_for_delivery';
    public const DELIVERED_TO_PICKUP_POINT = 'delivered_to_pickup_point';
    public const DELIVERED = 'delivered';
    public const LOST = 'lost';
}
