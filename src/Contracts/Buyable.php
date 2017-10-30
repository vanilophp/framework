<?php
/**
 * Contains the base Buyable interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-28
 *
 */

namespace Vanilo\Cart\Contracts;

interface Buyable
{
    /**
     * Returns the id of the _thing_
     *
     * @return int
     */
    public function getId();

    /**
     * Returns the name of the _thing_
     *
     * @return string
     */
    public function name();

    /**
     * Returns the price of the item; float is temporary!!
     *
     * @todo Make the decision with Vanilo 0.2 about how to handle prices:
     *       Decimal/Money/PreciseMoney/json with various currencies/...
     *
     * @return float
     */
    public function getPrice();
}
