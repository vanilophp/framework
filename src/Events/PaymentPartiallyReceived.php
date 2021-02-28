<?php

declare(strict_types=1);

/**
 * Contains the PaymentPartiallyReceived class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-30
 *
 */

namespace Vanilo\Payment\Events;

use Vanilo\Payment\Contracts\Payment;

class PaymentPartiallyReceived extends BasePaymentEvent
{
    public float $amountPaid;

    public function __construct(Payment $payment, float $amountPaid)
    {
        parent::__construct($payment);
        $this->amountPaid = $amountPaid;
    }
}
