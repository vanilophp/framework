<?php
/**
 * Contains the Order interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */


namespace Vanilo\Order\Contracts;


use Traversable;
use Vanilo\Contracts\Address;

interface Order
{
    /**
     * Returns the number of the order
     *
     * @return string
     */
    public function getNumber();

    public function getStatus(): OrderStatus;

    public function getBillingAddress(): Address;

    public function getShippingAddress(): Address;

    public function getItems(): Traversable;

}
