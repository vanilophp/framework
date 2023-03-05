<?php

declare(strict_types=1);

/**
 * Contains the ShippingFeeCalculator interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-28
 *
 */

namespace Vanilo\Shipment\Contracts;

use Vanilo\Contracts\Shippable;
use Vanilo\Shipment\Models\ShippingFee;

interface ShippingFeeCalculator
{
    public static function getName(): string;

    public function calculate(Shippable $shippable, ?array $configuration = null): ShippingFee;
}
