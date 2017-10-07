<?php
/**
 * Contains the Product interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */


namespace Konekt\Product\Contracts;


interface Product
{
    /**
     * Returns whether the product is active (based on its state)
     *
     * @return bool
     */
    public function isActive();

}