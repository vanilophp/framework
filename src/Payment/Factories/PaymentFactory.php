<?php

declare(strict_types=1);

/**
 * Contains the PaymentFactory class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-30
 *
 */

namespace Vanilo\Payment\Factories;

use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentMethod;
use Vanilo\Payment\Events\PaymentCreated;
use Vanilo\Payment\Models\PaymentProxy;

class PaymentFactory
{
    public static function createFromPayable(
        Payable $payable,
        PaymentMethod $paymentMethod,
        array $extraData = []
    ): Payment {
        $payment = PaymentProxy::create([
            'amount' => $payable->getAmount(),
            'currency' => $payable->getCurrency(),
            'payable_type' => $payable->getPayableType(),
            'payable_id' => $payable->getPayableId(),
            'payment_method_id' => $paymentMethod->id,
            'data' => $extraData
        ]);

        event(new PaymentCreated($payment));

        return $payment;
    }
}
