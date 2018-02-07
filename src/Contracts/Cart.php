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

use Illuminate\Contracts\Auth\Authenticatable;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\CheckoutSubject;

interface Cart extends CheckoutSubject
{
    /**
     * Add an item to the cart (or adds the quantity if the product is already in the cart)
     *
     * @param Buyable $product Any Buyable object
     * @param int $qty The quantity to add
     * @param array $params Additional parameters, eg. coupon code
     *
     * @return CartItem Returns the item object that has been created (or updated)
     */
    public function addItem(Buyable $product, $qty = 1, $params = []) : CartItem;

    /**
     * Removes an item from the cart
     *
     * @param object|int    $item    Object: item or int = item id
     */
    public function removeItem($item);

    /**
     * Removes a product from the cart
     *
     * @param Buyable $product
     */
    public function removeProduct(Buyable $product);

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

    /**
     * Returns the cart's associated user, or NULL
     *
     * @return Authenticatable|null
     */
    public function getUser();

    /**
     * Set the user of the cart
     *
     * @param Authenticatable|integer $user User object or user id
     */
    public function setUser($user);
}
