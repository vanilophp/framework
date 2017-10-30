<?php
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
     *
     * @return bool
     */
    public function exists();

    /**
     * Returns true if neither the session nor the user has a cart
     *
     * @return bool
     */
    public function doesNotExist();

    /**
     * Returns the encapsulated ActiveRecord model or null if not exists
     *
     * @return Cart|null
     */
    public function model();
}
