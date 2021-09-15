<?php

declare(strict_types=1);

/**
 * Contains the PaymentEventMap interface.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-12
 *
 */

namespace Vanilo\Payment\Contracts;

use Vanilo\Payment\Models\PaymentStatus;

interface PaymentEventMap
{
    public function ifCurrentStatusIs(PaymentStatus $status): self;

    public function andOldStatusIs(PaymentStatus $status): self;

    public function thenFireEvents(): array;
}
