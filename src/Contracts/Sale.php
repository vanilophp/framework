<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

use Traversable;

/**
 * A Sale represents a completed commercial exchange
 * Typically orders, but it can be a subscription
 * a POS transaction, subscription renewal, etc
 */
interface Sale
{
    public function getBillpayer(): ?BillPayer;

    public function getItems(): Traversable;

    public function itemsTotal(): float;

    public function total(): float;
}
