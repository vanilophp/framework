<?php

declare(strict_types=1);

/**
 * Contains the NoTransaction class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-06-04
 *
 */

namespace Vanilo\Payment\Responses;

use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\TransactionNotCreated;
use Vanilo\Payment\Models\PaymentProxy;

final class NoTransaction implements TransactionNotCreated
{
    public mixed $details = null;

    private readonly string $paymentId;

    private ?Payment $payment = null;

    private ?string $reason = null;

    private bool $shouldBeRetried = false;

    public function __construct(
        Payment|string $payment,
    ) {
        if ($payment instanceof Payment) {
            $this->paymentId = $payment->getPaymentId();
            $this->payment = $payment;
        } else {
            $this->paymentId = $payment;
        }
    }

    public static function create(Payment|string $payment, ?string $reason, bool $shouldBeRetried = false): self
    {
        $instance = new self($payment);
        $instance->reason = $reason;
        $instance->shouldBeRetried = $shouldBeRetried;

        return $instance;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getPayment(): Payment
    {
        if (null === $this->payment) {
            $this->payment = PaymentProxy::find($this->paymentId);
        }

        return $this->payment;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    public function shouldBeRetried(): bool
    {
        return $this->shouldBeRetried;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
