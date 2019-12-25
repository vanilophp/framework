<?php
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

use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;

class PlasticPayments implements PaymentGateway
{
    public static function getName(): string
    {
        return 'Plastic Payments';
    }

    public function createPaymentRequest(Payable $payable): PaymentRequest
    {
        // TODO: Implement createPaymentRequest() method.
    }

    public function isOffline(): bool
    {
        return false;
    }
}
