<?php

declare(strict_types=1);

/**
 * Contains the NullResponse class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-26
 *
 */

namespace Vanilo\Payment\Responses;

use Konekt\Enum\Enum;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Payment\Models\PaymentStatusProxy;

class NullResponse implements PaymentResponse
{
    public function wasSuccessful(): bool
    {
        return true;
    }

    public function getMessage(): ?string
    {
        return null;
    }

    public function getTransactionId(): ?string
    {
        return null;
    }

    public function getAmountPaid(): ?float
    {
        return null;
    }

    public function getPaymentId(): string
    {
        return '';
    }

    public function getStatus(): PaymentStatus
    {
        return PaymentStatusProxy::create(); // Returns the default value of the enum
    }

    public function getNativeStatus(): Enum
    {
        return NullStatus::create();
    }
}
