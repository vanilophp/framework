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
    /** @todo Move this to the Gateway in v5 along with all future supports<Feature> methods */
    public function supportsRefunds(): bool;

    public function supportsRetry(): bool;

    public function allowsRefund(Payment $payment): bool;

    public function issueRefund(Payment $payment, float $amount, array $options = []): Transaction|TransactionNotCreated;

    public function canBeRetried(Payment $payment): bool;

    public function getRetryRequest(Payment $payment, array $options = []): PaymentRequest|TransactionNotCreated;
}
