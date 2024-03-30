<?php

declare(strict_types=1);

/**
 * Contains the OrderFactory interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */

namespace Vanilo\Order\Contracts;

interface OrderFactory
{
    /**
     * Creates a new order from simple data arrays
     */
    public function createFromDataArray(array $data, array $items, array|callable $hooks = null, array|callable $itemHooks = null): Order;
}
