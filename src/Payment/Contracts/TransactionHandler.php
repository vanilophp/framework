<?php

declare(strict_types=1);

/**
 * Contains the TransactionHandler interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-24
 *
 */

namespace Vanilo\Payment\Contracts;

/**
 * @experimental don't use it yet, don't rely on it yet!
 */
interface TransactionHandler
{
    public function supportsRefunds(): bool;

    public function issueRefund(Payment $payment, float $amount, array $options = []): Transaction|TransactionNotCreated;
}
