<?php

declare(strict_types=1);

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

use Illuminate\Queue\SerializesModels;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderAwareEvent;

abstract class BaseOrderEvent implements OrderAwareEvent
{
    use HasOrder;
    use SerializesModels;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
