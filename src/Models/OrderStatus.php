<?php
/**
 * Contains the OrderStatus enum class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */


namespace Vanilo\Order\Models;


use Konekt\Enum\Enum;

class OrderStatus extends Enum
{
    const __default = self::PENDING;

    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    const PENDING = 'pending';

    /**
     * Orders fulfilled completely.
     */
    const COMPLETED = 'complete';

    /**
     * Order that has been cancelled.
     */
    const CANCELLED = 'cancelled';


}