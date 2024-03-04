<?php

declare(strict_types=1);

/**
 * Contains the BillpayerChanged class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-03-04
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Contracts\Billpayer;

class BillpayerChanged extends BaseCheckoutEvent
{
    public function newBillpayer(): Billpayer
    {
        return $this->checkout->getBillpayer();
    }
}
