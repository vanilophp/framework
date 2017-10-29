<?php
/**
 * Contains the CartManager class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart;


use Vanilo\Cart\Contracts\CartManager as CartManagerContract;

class CartManager implements CartManagerContract
{
    /**
     * @inheritDoc
     */
    public function addItem($product, $qty = 1, $params = [])
    {
        // TODO: Implement addItem() method.
    }

    /**
     * @inheritDoc
     */
    public function removeItem($item)
    {
        // TODO: Implement removeItem() method.
    }

    /**
     * @inheritDoc
     */
    public function removeProduct($product)
    {
        // TODO: Implement removeProduct() method.
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * @inheritDoc
     */
    public function itemCount()
    {
        // TODO: Implement itemCount() method.
    }

    /**
     * @inheritDoc
     */
    public function exists()
    {
        // TODO: Implement exists() method.
    }

    /**
     * @inheritDoc
     */
    public function doesNotExist()
    {
        // TODO: Implement doesNotExist() method.
    }

    /**
     * @inheritDoc
     */
    public static function model()
    {
        // TODO: Implement model() method.
    }


}