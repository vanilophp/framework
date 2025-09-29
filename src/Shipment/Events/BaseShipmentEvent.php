<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Events;

use Illuminate\Queue\SerializesModels;
use Vanilo\Shipment\Contracts\Shipment;
use Vanilo\Shipment\Contracts\ShipmentEvent;

abstract class BaseShipmentEvent implements ShipmentEvent
{
    use SerializesModels;

    public function __construct(
        public Shipment $shipment,
    ) {
    }

    public function getShipment(): Shipment
    {
        return $this->shipment;
    }
}
