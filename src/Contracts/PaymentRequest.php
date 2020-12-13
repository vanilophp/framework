<?php
/**
 * Contains the PaymentRequest interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Payment\Contracts;

interface PaymentRequest
{
    /* Returns the html snippet to be rendered for initiating the payment */
    public function getHtmlSnippet(array $options = []): ?string;

    public function willRedirect(): bool;
}
