<?php
/**
 * Contains the Cart class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart\Contracts;

interface Cart
{
    /**
     * Add an item to the cart
     *
     * @param Buyable|int|string    $product    Either a Buyable object or int = product id or string = SKU
     * @param int                   $qty        The quantity to add
     * @param array                 $params     Additional parameters, eg. coupon code
     */
    public function addItem($product, $qty = 1, $params = []);

    /**
     * Removes an item from the cart
     *
     * @param object|int    $item    Object: item or int = item id
     */
    public function removeItem($item);

    /**
     * Removes a product from the cart
     *
     * @param Buyable|int|string    $product    Either a Buyable object or int = product id or string = SKU
     */
    public function removeProduct($product);

    /**
     * Clears the entire cart
     */
    public function clear();

    /**
     * Returns the number of items in the cart
     *
     * @return int
     */
    public function itemCount();
}
