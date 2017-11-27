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


interface Order
{
    /**
     * Returns the number of the order
     *
     * @return string
     */
    public function getNumber();

}
