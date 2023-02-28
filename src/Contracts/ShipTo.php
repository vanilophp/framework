<?php

declare(strict_types=1);

/**
 * Contains the ShipTo interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-28
 *
 */

namespace Vanilo\Contracts;

interface ShipTo extends Customer
{
    public function getShippingAddress(): Address;

    public function getNotes(): string;
}
