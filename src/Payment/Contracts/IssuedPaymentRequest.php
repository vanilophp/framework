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

use Illuminate\Support\Collection;

interface IssuedPaymentRequest
{
    public static function ofPayment(Payment $payment): Collection;

    public function hasSnapshot(): bool;
}
