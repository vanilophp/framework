<?php
/**
 * Contains the ProductState enum interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Contracts;

interface ProductState
{
    /**
     * Returns whether the state represents an active state
     *
     * @return boolean
     */
    public function isActive(): bool;

    /**
     * Returns an array of states that represent an active product state
     *
     * @return array
     */
    public static function getActiveStates(): array;
}
