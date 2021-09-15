<?php

declare(strict_types=1);

/**
 * Contains the PaymentEvent interface.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Contracts;

interface PaymentEvent
{
    public function getPayment(): Payment;
}
