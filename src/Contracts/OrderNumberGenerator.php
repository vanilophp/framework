<?php
/**
 * Contains the OrderNumberGenerator interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Contracts;


interface OrderNumberGenerator
{
    /**
     * Generates and returns a new order number.
     *
     * @param Order|null $order
     *
     * @return string
     */
    public function generateNumber(Order $order = null);

}