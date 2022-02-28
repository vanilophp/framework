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

/**
 * @property-read bool $is_new
 * @property-read bool $is_info
 * @property-read bool $is_ready
 * @property-read bool $is_cancelled
 * @property-read bool $is_picked_up
 * @property-read bool $is_hub_scan
 * @property-read bool $is_out_for_delivery
 * @property-read bool $is_delivered_to_pickup_point
 * @property-read bool $is_delivered
 * @property-read bool $is_failed_attempt
 * @property-read bool $is_final_attempt_failed
 * @property-read bool $is_lost
 *
 * @method static ShipmentStatus NEW()
 * @method static ShipmentStatus INFO()
 * @method static ShipmentStatus READY()
 * @method static ShipmentStatus CANCELLED()
 * @method static ShipmentStatus PICKED_UP()
 * @method static ShipmentStatus HUB_SCAN()
 * @method static ShipmentStatus OUT_FOR_DELIVERY()
 * @method static ShipmentStatus DELIVERED_TO_PICKUP_POINT()
 * @method static ShipmentStatus DELIVERED()
 * @method static ShipmentStatus FAILED_ATTEMPT()
 * @method static ShipmentStatus FINAL_ATTEMPT_FAILED()
 * @method static ShipmentStatus LOST()
 */
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
    public const FAILED_ATTEMPT = 'failed_attempt';
    public const FINAL_ATTEMPT_FAILED = 'final_attempt_failed';
    public const LOST = 'lost';
}
