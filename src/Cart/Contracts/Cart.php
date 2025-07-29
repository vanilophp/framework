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
use Illuminate\Support\Collection;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\CheckoutSubject;

interface Cart extends CheckoutSubject
{
    /**
     * Returns the cart items which are "root" items i.e., have no parent
     *
     * @return Collection<CartItem>
     */
    public function getRootItems(): Collection;

    /**
     * Add an item to the cart (or adds the quantity if the product is already in the cart)
     *
     * @param Buyable $product Any Buyable object
     * @param int|float $qty The quantity to add
     * @param array $params Additional parameters, eg. coupon code
     * @param bool $forceNewItem If true, a new item will be created even if the same product exists in cart
     *
     * @return CartItem Returns the item object that has been created (or updated)
     */
    public function addItem(Buyable $product, int|float $qty = 1, array $params = [], bool $forceNewItem = false): CartItem;

    public function addSubItem(CartItem $parent, Buyable $product, int|float $qty = 1, array $params = []): CartItem;

    public function removeItem(CartItem $item): void;

    public function removeProduct(Buyable $product): void;

    public function clear(): void;

    public function itemCount(): int;

    public function getState(): ?CartState;

    public function getUser(): ?Authenticatable;

    public function setUser(Authenticatable|int|string|null $user): void;
}
