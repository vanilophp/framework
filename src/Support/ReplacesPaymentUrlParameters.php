<?php

declare(strict_types=1);

/**
 * Contains the ReplacesPaymentUrlParameters trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-19
 *
 */

namespace Vanilo\Payment\Support;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Vanilo\Payment\Contracts\Payment;

trait ReplacesPaymentUrlParameters
{
    private function replaceUrlParameters(string $url, Payment $payment): string
    {
        $substitutions = [
            'paymentId' => $payment->getPaymentId(),
            'payableId' => $payment->getPayable()->getPayableId(),
        ];

        $result = $url;
        foreach ($substitutions as $param => $replacement) {
            $result = str_replace('{' . $param . '}', $replacement, $result);
        }

        if (!Str::startsWith($result, 'http')) {
            $result = URL::to($result);
        }

        return $result;
    }
}
