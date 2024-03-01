<?php

declare(strict_types=1);

/**
 * Contains the CartManager interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-28
 *
 */

namespace Vanilo\Cart\Contracts;

interface CartManager extends Cart
{
    /**
     * Returns whether a cart exists for this user or session
     */
    public function exists(): bool;

    /**
     * Returns true if neither the session nor the user has a cart
     */
    public function doesNotExist(): bool;

    /**
     * Returns the encapsulated ActiveRecord model or null if not exists
     */
    public function model(): ?Cart;

    /**
     * Returns true if the cart is empty (doesn't contain items)
     */
    public function isEmpty(): bool;

    /**
     * Returns true if the cart is not empty (contains items)
     */
    public function isNotEmpty(): bool;

    /**
     * Completely destroys the cart: removes all related models (cart, item, etc) from the DB
     */
    public function destroy(): void;

    /**
     * Creates a new cart
     *
     * @param bool $forceCreateIfExists Creates a new cart even if there's an existing one
     */
    public function create(bool $forceCreateIfExists = false): void;

    /**
     * Dissociates a user from a cart
     */
    public function removeUser(): void;

    /**
     * Forget the cart for the session, but keep it
     */
    public function forget(): void;
}
