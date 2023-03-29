<?php

declare(strict_types=1);

/**
 * Contains the Shipment class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-20
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderItem as OrderItemContract;
use Vanilo\Order\Models\OrderItemProxy;
use Vanilo\Order\Models\OrderProxy;
use Vanilo\Shipment\Models\Shipment as BaseShipment;

class Shipment extends BaseShipment
{
    public function orders(): MorphToMany
    {
        return $this->morphedByMany(OrderProxy::modelClass(), 'shippable');
    }

    public function orderItems(): MorphToMany
    {
        return $this->morphedByMany(OrderItemProxy::modelClass(), 'shippable');
    }

    public function addOrder(OrderContract $order): static
    {
        $this->orders()->save($order);

        return $this;
    }

    public function addOrderItem(OrderItemContract $orderItem): static
    {
        $this->orderItems()->save($orderItem);

        return $this;
    }
}
