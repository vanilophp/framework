<?php

declare(strict_types=1);

/**
 * Contains the HasPayment trait.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Events;

use Vanilo\Payment\Contracts\Payment;

trait HasPayment
{
    protected Payment $payment;

    public function getPayment(): Payment
    {
        return $this->payment;
    }
}
