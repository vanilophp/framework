<?php

declare(strict_types=1);

/**
 * Contains the PaymentMethodSelected event class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-11-11
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Checkout\Contracts\Checkout;

class PaymentMethodSelected extends BaseCheckoutEvent
{
    protected null|int|string $oldPaymentMethodId;

    protected null|int|string $newPaymentMethodId;

    public function __construct(Checkout $checkout, null|int|string $oldPaymentMethodId)
    {
        parent::__construct($checkout);
        $this->oldPaymentMethodId = $oldPaymentMethodId;
        $this->newPaymentMethodId = $checkout->getPaymentMethodId();
    }

    public function selectedPaymentMethodId(): null|int|string
    {
        return $this->newPaymentMethodId;
    }

    public function oldPaymentMethodId(): null|int|string
    {
        return $this->oldPaymentMethodId;
    }
}
