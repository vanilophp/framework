<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethod class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-08
 *
 */

namespace Vanilo\Foundation\Models;

use Vanilo\Channel\Traits\Channelable;
use Vanilo\Shipment\Models\ShippingMethod as BaseShippingMethod;

class ShippingMethod extends BaseShippingMethod
{
    use Channelable;
}
