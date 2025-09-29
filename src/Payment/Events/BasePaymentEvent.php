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

use Illuminate\Queue\SerializesModels;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentEvent;

/** @todo v6 make this class abstract */
class BasePaymentEvent implements PaymentEvent
{
    use HasPayment;
    use SerializesModels;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
