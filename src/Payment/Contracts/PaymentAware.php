<?php

declare(strict_types=1);

/**
 * Contains the PaymentAware interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-24
 *
 */

namespace Vanilo\Payment\Contracts;

/**
 * @experimental
 */
interface PaymentAware
{
    public function getPaymentId(): string;

    public function getPayment(): Payment;
}
