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
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;

class ShippingMethod extends BaseShippingMethod implements Taxable
{
    use BelongsToTaxCategory;
    use Channelable;
}
