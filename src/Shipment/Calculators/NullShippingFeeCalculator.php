<?php

declare(strict_types=1);

/**
 * Contains the NullShippingFeeCalculator class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Shipment\Calculators;

use Vanilo\Contracts\Shippable;
use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Models\ShippingFee;

class NullShippingFeeCalculator implements ShippingFeeCalculator
{
    public static function getName(): string
    {
        return __('No shipping fee');
    }

    public function calculate(Shippable $shippable, ?array $configuration = null): ShippingFee
    {
        return new ShippingFee(0);
    }
}
