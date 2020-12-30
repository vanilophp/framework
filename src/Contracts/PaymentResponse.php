<?php

declare(strict_types=1);

/**
 * Contains the PaymentResponse interface.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-26
 *
 */

namespace Vanilo\Payment\Contracts;

interface PaymentResponse
{
    public function wasSuccessful(): bool;

    public function getMessage(): ?string;

    public function getTransactionId(): ?string;

    public function getAmountPaid(): ?float;

    public function getPaymentId(): string;
}
