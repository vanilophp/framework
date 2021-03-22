<?php

declare(strict_types=1);

/**
 * Contains the PaymentHistory interface.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-21
 *
 */

namespace Vanilo\Payment\Contracts;

interface PaymentHistory
{
    public static function begin(Payment $payment): PaymentHistory;

    public static function addPaymentResponse(
        Payment $payment,
        PaymentResponse $response,
        PaymentStatus $oldStatus = null
    ): PaymentHistory;
}
