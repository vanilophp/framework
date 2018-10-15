<?php
/**
 * Contains the CartState interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-15
 *
 */

namespace Vanilo\Cart\Contracts;

interface CartState
{
    /**
     * Returns whether the cart's state represents an active state
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Returns the array of active states
     *
     * @return array
     */
    public static function getActiveStates(): array;
}
