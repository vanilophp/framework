<?php
/**
 * Contains the BuyableModel trait.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */


namespace Vanilo\Cart\Traits;

/**
 * Trait for Eloquent Models to implement the Buyable interface
 */
trait BuyableModel
{
    public function getId()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
