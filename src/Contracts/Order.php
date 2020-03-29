<?php
/**
 * Contains the Order interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */

namespace Vanilo\Order\Contracts;

use Traversable;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\BillPayer;

interface Order
{
    public function getNumber(): ?string;

    public function getStatus(): OrderStatus;

    public function getBillpayer(): ?BillPayer;

    public function getShippingAddress(): ?Address;

    public function getItems(): Traversable;

    /**
     * Returns the final total of the Order
     *
     * @return float
     */
    public function total();
}
