<?php

declare(strict_types=1);

/**
 * Contains the BasePaymentEvent class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Events;

use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentEvent;

class BasePaymentEvent implements PaymentEvent
{
    use HasPayment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
