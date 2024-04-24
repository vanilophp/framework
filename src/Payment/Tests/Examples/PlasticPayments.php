<?php

declare(strict_types=1);

/**
 * Contains the PlasticPayments class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-26
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
use Vanilo\Payment\Requests\NullRequest;
use Vanilo\Payment\Responses\NullResponse;

class PlasticPayments implements PaymentGateway
{
    public static function getName(): string
    {
        return 'Plastic Payments';
    }

    public static function svgIcon(): string
    {
        return '';
    }

    public function createPaymentRequest(
        Payment $payment,
        Address $shippingAddress = null,
        array $options = []
    ): PaymentRequest {
        return new NullRequest($payment);
    }

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse
    {
        return new NullResponse();
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
