<?php

declare(strict_types=1);

/**
 * Contains the FailedOperation interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-24
 *
 */

namespace Vanilo\Payment\Contracts;

/**
 * @experimental
 */
interface TransactionNotCreated extends PaymentAware
{
    public function reason(): ?string;

    public function shouldBeRetried(): bool;

    public function getDetails();
}
