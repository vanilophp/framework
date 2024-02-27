<?php

declare(strict_types=1);

/**
 * Contains the CheckoutSubjectItem interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-03
 *
 */

namespace Vanilo\Contracts;

interface CheckoutSubjectItem extends Configurable
{
    /**
     * Returns the buyable (product) of the item
     */
    public function getBuyable(): Buyable;

    /**
     * Returns the quantity of the line
     */
    public function getQuantity(): int;

    /**
     * Returns the (adjusted) line total
     */
    public function total(): float;
}
