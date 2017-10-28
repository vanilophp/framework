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

namespace Konekt\Cart\Contracts;


interface Buyable
{
    /**
     * Returns the name of the _thing_
     *
     * @return string
     */
    public function name();

    /**
     * @return Money?? or what??
     */
    public function getPrice();
}
