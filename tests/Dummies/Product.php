<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart\Tests\Dummies;

use Vanilo\Cart\Contracts\Buyable;

class Product implements Buyable
{
    protected $name;

    protected $price;

    public $id;

    public function __construct($name, $price, $id = null)
    {
        $this->name  = $name;
        $this->price = $price;
        $this->id    = $id ?: 1;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->price;
    }
}
