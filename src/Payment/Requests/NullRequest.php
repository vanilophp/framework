<?php

declare(strict_types=1);

/**
 * Contains the NullRequest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Requests;

use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentRequest;

class NullRequest implements PaymentRequest
{
    private Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function getHtmlSnippet(array $options = []): ?string
    {
        return '';
    }

    public function willRedirect(): bool
    {
        return false;
    }

    public function getRemoteId(): ?string
    {
        return null;
    }
}
