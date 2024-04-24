<?php

declare(strict_types=1);

/**
 * Contains the UnorthodoxGateway class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-22
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Illuminate\Http\Request;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\TransactionHandler;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\Requests\NullRequest;

class UnorthodoxGateway implements PaymentGateway
{
    public static function getName(): string
    {
        return 'Unorthodox Payments';
    }

    public function createPaymentRequest(
        Payment $payment,
        Address $shippingAddress = null,
        array $options = []
    ): PaymentRequest {
        return new NullRequest($payment);
    }

    public function processPaymentResponse(Request|UnorthodoxPaymentResponse $request, array $options = []): PaymentResponse
    {
        return new SomePaymentResponse(
            'Works',
            true,
            $request->transactionId,
            $request->amount,
            $request->transactionId,
            $request->getStatus(),
            PaymentStatus::PAID()
        );
    }

    public function transactionHandler(): ?TransactionHandler
    {
        return null;
    }

    public function isOffline(): bool
    {
        return false;
    }
}
