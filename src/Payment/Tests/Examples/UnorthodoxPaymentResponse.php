<?php

declare(strict_types=1);

/**
 * Contains the UnorthodoxPaymentResponse class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-22
 *
 */

namespace Vanilo\Payment\Tests\Examples;

class UnorthodoxPaymentResponse
{
    public function __construct(
        public string $transactionId,
        public float $amount,
    ) {
    }

    public function getStatus(): SomeNativeStatus
    {
        return SomeNativeStatus::CAPTURED();
    }
}
