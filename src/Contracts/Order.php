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
use Vanilo\Contracts\BillPayer;

interface Order
{
    /**
     * Returns the number of the order
     *
     * @return string
     */
    public function getNumber();

    public function getStatus(): OrderStatus;

    /**
     * @return BillPayer|null
     */
    public function getBillpayer();

    /**
     * @return Address|null
     */
    public function getShippingAddress();

    public function getItems(): Traversable;

    /**
     * Returns the final total of the Order
     *
     * @return float
     */
    public function total();

}
