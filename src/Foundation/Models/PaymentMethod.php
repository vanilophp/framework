<?php

declare(strict_types=1);

/**
 * Contains the PaymentMethod class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-08
 *
 */

namespace Vanilo\Foundation\Models;

use Konekt\Address\Concerns\Zoneable;
use Vanilo\Channel\Traits\Channelable;
use Vanilo\Payment\Models\PaymentMethod as BasePaymentMethod;

class PaymentMethod extends BasePaymentMethod
{
    use Channelable;
    use Zoneable;
}
