<?php
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
     *
     * @param array $data
     * @param array $items
     *
     * @return Order
     */
    public function createFromDataArray(array $data, array $items) : Order;
}