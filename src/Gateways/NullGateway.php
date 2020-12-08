<?php

declare(strict_types=1);

/**
 * Contains the NullGateway class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Gateways;

use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Requests\NullRequest;

class NullGateway implements PaymentGateway
{
    public static function getName(): string
    {
        return __('Offline');
    }

    public function createPaymentRequest(Payable $payable): PaymentRequest
    {
        return new NullRequest($payable);
    }

    public function isOffline(): bool
    {
        return true;
    }
}
