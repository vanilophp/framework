<?php
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

use Vanilo\Contracts\Payable;

interface PaymentGateway
{
    public static function getName(): string;

    public function createPaymentRequest(Payable $payable): PaymentRequest;

    public function isOffline(): bool;
}
