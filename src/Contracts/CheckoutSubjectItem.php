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
    public function getBuyable(): Buyable;

    public function getQuantity(): int;

    public function total(): float;

    public function isShippable(): ?bool;
}
