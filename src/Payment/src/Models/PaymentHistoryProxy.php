<?php

declare(strict_types=1);

/**
 * Contains the PaymentHistoryProxy class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-21
 *
 */

namespace Vanilo\Payment\Models;

use Konekt\Concord\Proxies\ModelProxy;

/**
 * @method static \Vanilo\Payment\Contracts\PaymentHistory begin(Payment $payment);
 * @method static \Vanilo\Payment\Contracts\PaymentHistory addPaymentResponse(\Vanilo\Payment\Contracts\Payment $payment, \Vanilo\Payment\Contracts\PaymentResponse $response, \Vanilo\Payment\Contracts\PaymentStatus $oldStatus = null)
 */
class PaymentHistoryProxy extends ModelProxy
{
}
