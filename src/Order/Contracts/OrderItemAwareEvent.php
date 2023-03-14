<?php

declare(strict_types=1);

/**
 * Contains the OrderItemAwareEvent interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-14
 *
 */

namespace Vanilo\Order\Contracts;

interface OrderItemAwareEvent extends OrderAwareEvent
{
    public function getOrderItem(): OrderItem;
}
