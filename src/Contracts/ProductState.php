<?php
/**
 * Contains the ProductState interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */


namespace Konekt\Product\Contracts;

interface ProductState
{
    /**
     * Returns whether the state represents an active state
     *
     * @return boolean
     */
    public function isActive();
}
