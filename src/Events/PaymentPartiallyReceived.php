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

class PaymentPartiallyReceived extends BasePaymentEvent
{
    /** @var float */
    public $amountPaid;
}
