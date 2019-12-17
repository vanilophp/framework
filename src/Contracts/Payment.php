<?php
/**
 * Contains the Payment interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Payment\Contracts;

use Vanilo\Contracts\Payable;

interface Payment extends Payable
{
    public function getStatus(): PaymentStatus;

    public function getMethod(): PaymentMethod;
}
