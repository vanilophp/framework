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


namespace Vanilo\Support\Traits;

/**
 * Trait for Eloquent Models with default implementation of the Buyable interface
 */
trait BuyableModel
{
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function morphTypeName(): string
    {
        return shorten(static::class);
    }
}
