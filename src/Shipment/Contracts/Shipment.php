<?php

declare(strict_types=1);

/**
 * Contains the Shipment interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-16
 *
 */

namespace Vanilo\Shipment\Contracts;

use Vanilo\Contracts\Address;
use Vanilo\Contracts\Configurable;

interface Shipment extends Configurable
{
    public function deliveryAddress(): Address;

    public function status(): ShipmentStatus;

    public function getCarrier(): ?Carrier;
}
