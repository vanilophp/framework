<?php

declare(strict_types=1);

/**
 * Contains the SomeUrlProcessor class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-19
 *
 */

namespace Vanilo\Payment\Tests\Examples;

use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Support\ReplacesPaymentUrlParameters;

class SomeUrlProcessor
{
    use ReplacesPaymentUrlParameters;

    public function processUrl(string $url, Payment $payment): string
    {
        return $this->replaceUrlParameters($url, $payment);
    }
}
