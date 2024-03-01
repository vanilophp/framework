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

use Vanilo\Contracts\Configurable;

interface OrderItem extends Configurable
{
    public function total(): float;

    public function getOrder(): Order;

    public function getProduct(): object;

    public function getFulfillmentStatus(): FulfillmentStatus;

    public function getName(): string;

    public function getQuantity(): float;

    public function getPrice(): float;
}
