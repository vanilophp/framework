<?php

declare(strict_types=1);

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
     * @param int|float $qty The quantity to add
     * @param array $params Additional parameters, eg. coupon code
     *
     * @return CartItem Returns the item object that has been created (or updated)
     */
    public function addItem(Buyable $product, int|float $qty = 1, array $params = []): CartItem;

    public function addSubItem(CartItem $parent, Buyable $product, int|float $qty = 1, array $params = []): CartItem;

    /**
     * Removes an item from the cart
     */
    public function removeItem(CartItem $item): void;

    /**
     * Removes a product from the cart
     */
    public function removeProduct(Buyable $product): void;

    /**
     * Clears the entire cart
     */
    public function clear(): void;

    /**
     * Returns the number of items in the cart
     */
    public function itemCount(): int;

    /**
     * Returns the cart's associated user, or NULL
     */
    public function getUser(): ?Authenticatable;

    /**
     * Set the user of the cart by passing a user object or user id
     */
    public function setUser(Authenticatable|int|string|null $user): void;
}
