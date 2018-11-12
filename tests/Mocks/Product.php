<?php
/**
 * Contains the Product test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */

namespace Vanilo\Checkout\Tests\Mocks;

use Carbon\Carbon;
use Vanilo\Contracts\Buyable;

class Product implements Buyable
{
    public $id;

    public $name;

    public $price;

    public function __construct($id, $name, $price)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->price = $price;
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
    public function getName()
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

    /**
     * @inheritDoc
     */
    public function hasImage()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getThumbnailUrl()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getImageUrl()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function morphTypeName(): string
    {
        return 'product';
    }

    public function addSale(Carbon $date, $units = 1)
    {
        // not implemented here
    }

    public function removeSale($units = 1)
    {
        // not implemented here
    }
}
