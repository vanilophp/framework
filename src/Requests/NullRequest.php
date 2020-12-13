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

use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\PaymentRequest;

class NullRequest implements PaymentRequest
{
    /** @var Payable */
    private $payable;

    public function __construct(Payable $payable)
    {
        $this->payable = $payable;
    }

    public function getHtmlSnippet(array $options = []): ?string
    {
        return '';
    }

    public function willRedirect(): bool
    {
        return false;
    }
}
