<?php
/**
 * Contains the BaseOrderEvent class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */

namespace Vanilo\Order\Events;

use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderAwareEvent;

abstract class BaseOrderEvent implements OrderAwareEvent
{
    use HasOrder;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
