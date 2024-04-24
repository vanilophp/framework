<?php

declare(strict_types=1);

/**
 * Contains the Transaction interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-24
 *
 */

namespace Vanilo\Payment\Contracts;

use StringBackedEnum;

/**
 * @experimental is subject to change. don't use it yet!
 */
interface Transaction extends PaymentAware
{
    public function wasSuccessful(): bool;

    public function message(): string;

    public function attemptedAmount(): float;

    public function transactedAmount(): float;

    public function isCharge(): bool;

    public function isCredit(): bool;

    public function getTransactionId(): ?string;

    public function getRemoteSubjectId(): ?string;

    public function getRemoteSubjectType(): ?string;

    public function getNativeStatus(): StringBackedEnum;
}
