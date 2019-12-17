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
    /** Returns the unique slug of the gateway type */
    public function getId(): string;

    public function getName(): string;

    public function createPaymentRequest(Payable $payable): PaymentRequest;

    public function isOffline(): bool;
}
