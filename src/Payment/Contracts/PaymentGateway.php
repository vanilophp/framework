<?php

declare(strict_types=1);

/**
 * Contains the PaymentGateway interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Payment\Contracts;

use Illuminate\Http\Request;
use Vanilo\Contracts\Address;

interface PaymentGateway
{
    public static function getName(): string;

    /** This method must return a 1:1 ratio svg as string */
    public static function svgIcon(): string;

    public function createPaymentRequest(
        Payment $payment,
        ?Address $shippingAddress = null,
        array $options = []
    ): PaymentRequest;

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse;

    /** @experimental  */
    public function transactionHandler(): ?TransactionHandler;

    public function isOffline(): bool;
}
