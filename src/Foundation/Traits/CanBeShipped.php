<?php

declare(strict_types=1);

/**
 * Contains the CanBeShipped trait.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-29
 *
 */

namespace Vanilo\Foundation\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Models\ShipmentProxy;

trait CanBeShipped
{
    public function shipments(): MorphToMany
    {
        return $this->morphToMany(ShipmentProxy::modelClass(), 'shippable')->withTimestamps();
    }

    public function addShipment(ShipmentContract $shipment): static
    {
        $this->shipments()->save($shipment);

        return $this;
    }

    public function addShipments(ShipmentContract ...$shipments)
    {
        $this->shipments()->saveMany($shipments);

        return $this;
    }
}
