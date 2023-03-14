<?php

declare(strict_types=1);

/**
 * Contains the OrderItemsIsReadyForPickup class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-14
 *
 */

namespace Vanilo\Order\Events;

// When the fulfillment status goes into READY_FOR_PICKUP
class OrderItemsIsReadyForPickup extends BaseOrderItemEvent
{
}
