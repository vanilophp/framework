<?php
/**
 * Contains the CheckoutSubject interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-03
 *
 */


namespace Vanilo\Contracts;

use Illuminate\Support\Collection;


/**
 * This one is typically a (readonly) cart
 */
interface CheckoutSubject
{
    /**
     * Returns a collection of CheckoutSubjectItems
     *
     * @return Collection
     */
    public function getItems(): Collection;

    /**
     * Returns the final total of the CheckoutSubject (typically a cart)
     *
     * @return float
     */
    public function total();
}
