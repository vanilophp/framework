<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethod interface.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-01-12
 *
 */

namespace Vanilo\Shipment\Contracts;

use Vanilo\Contracts\Configurable;
use Vanilo\Shipment\Models\ShippingFee;

interface ShippingMethod extends Configurable
{
    public function getCarrier(): ?Carrier;

    public function getCalculator(): ShippingFeeCalculator;

    public function estimate(?object $subject = null): ShippingFee;
}
