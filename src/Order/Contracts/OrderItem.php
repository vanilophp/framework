<?php

declare(strict_types=1);

/**
 * Contains the OrderItem interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */

namespace Vanilo\Order\Contracts;

use Vanilo\Contracts\SaleItem;

interface OrderItem extends SaleItem
{
    public function getOrder(): Order;

    /** @deprecated Use the `getBuyable()` method instead. Will be removed in Vanilo 6 */
    public function getProduct(): object;

    public function getFulfillmentStatus(): FulfillmentStatus;

    public function hasParent(): bool;

    public function getParent(): ?self;
}
