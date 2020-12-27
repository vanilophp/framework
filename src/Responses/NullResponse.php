<?php

declare(strict_types=1);

/**
 * Contains the NullResponse class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-26
 *
 */

namespace Vanilo\Payment\Responses;

use Vanilo\Payment\Contracts\PaymentResponse;

class NullResponse implements PaymentResponse
{
    public function wasSuccessful(): bool
    {
        return true;
    }

    public function getMessage(): ?string
    {
        return null;
    }

    public function getTransactionId(): ?string
    {
        return null;
    }

    public function getPayableId(): string
    {
        return '';
    }
}
