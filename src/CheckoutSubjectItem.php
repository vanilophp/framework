<?php
/**
 * Contains the CheckoutSubjectItem interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-03
 *
 */


namespace Vanilo\Contracts;


interface CheckoutSubjectItem
{
    /**
     * Returns the buyable (product) of the item
     *
     * @return Buyable
     */
    public function getBuyable(): Buyable;

    /**
     * Returns the quantity of the line
     *
     * @return integer
     */
    public function getQuantity();

    /**
     * Returns the (adjusted) line total
     *
     * @return float
     */
    public function total();
}
