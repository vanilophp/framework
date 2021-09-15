<?php

declare(strict_types=1);

/**
 * Contains the SomePaymentResponse class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-21
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Konekt\Enum\Enum;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;

class SomePaymentResponse implements PaymentResponse
{
    private string $message;

    private bool $wasSuccessful;

    private string $transactionId;

    private ?float $amountPaid;

    private string $paymentId;

    private SomeNativeStatus $nativeStatus;

    private PaymentStatus $status;

    public function __construct(
        string $message,
        bool $wasSuccessful,
        string $transactionId,
        ?float $amountPaid,
        string $paymentId,
        SomeNativeStatus $nativeStatus,
        PaymentStatus $status
    ) {
        $this->message = $message;
        $this->nativeStatus = $nativeStatus;
        $this->wasSuccessful = $wasSuccessful;
        $this->transactionId = $transactionId;
        $this->amountPaid = $amountPaid;
        $this->paymentId = $paymentId;
        $this->status = $status;
    }

    public function wasSuccessful(): bool
    {
        return $this->wasSuccessful;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function getNativeStatus(): Enum
    {
        return $this->nativeStatus;
    }
}
