<?php
/**
 * Contains the PaymentMethod interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Payment\Contracts;

interface PaymentMethod
{
    /**
     * Time in seconds after an initiated payment request is being considered as timed out
     *
     * @return int
     */
    public function getTimeout(): int;
}
