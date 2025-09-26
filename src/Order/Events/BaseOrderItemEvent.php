<?php

declare(strict_types=1);

/**
 * Contains the BaseOrderItemEvent class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-14
 *
 */

namespace Vanilo\Order\Events;

use Illuminate\Queue\SerializesModels;
use Vanilo\Order\Contracts\OrderItem;
use Vanilo\Order\Contracts\OrderItemAwareEvent;

class BaseOrderItemEvent implements OrderItemAwareEvent
{
    use HasOrder;
    use SerializesModels;

    protected OrderItem $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
        $this->order = $orderItem->order;
    }

    public function getOrderItem(): OrderItem
    {
        return $this->orderItem;
    }
}
