<?php

declare(strict_types=1);

/**
 * Contains the IssuedPaymentRequest interface.
 *
 * @copyright   Copyright (c) 2026 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2026-11-05
 *
 */

namespace Vanilo\Payment\Contracts;

interface IssuedPaymentRequest
{
    public function getPayment(): Payment;

    public function hasSnapshot(): bool;

    public function getSnapshotId(): ?int;
}
