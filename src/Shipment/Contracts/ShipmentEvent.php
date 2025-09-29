<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Contracts;

interface ShipmentEvent
{
    public function getShipment(): Shipment;
}
