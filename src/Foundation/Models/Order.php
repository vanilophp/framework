<?php

declare(strict_types=1);

/**
 * Contains the Order class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-13
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Channel\Models\Channel;
use Vanilo\Channel\Models\ChannelProxy;
use Vanilo\Contracts\Payable;
use Vanilo\Order\Models\Order as BaseOrder;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Models\PaymentProxy;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Models\ShipmentProxy;

/**
 * @property null|Payment $currentPayment is only available if order was fetched with withCurrentPayment() scope
 * @property null|int $channel_id
 * @property null|Channel $channel
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|ShipmentContract[] $shipments
 */
class Order extends BaseOrder implements Payable
{
    public function getPayableId(): string
    {
        return (string) $this->id;
    }

    public function getPayableType(): string
    {
        return 'order';
    }

    public function getTitle(): string
    {
        return $this->getNumber();
    }

    public function getAmount(): float
    {
        return $this->total();
    }

    public function getCurrency(): string
    {
        return config('vanilo.foundation.currency.code');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }

    public function getCurrentPayment(): ?Payment
    {
        /** If it has been fetched already, use it */
        if (null !== $this->currentPayment) {
            return $this->currentPayment;
        }

        return PaymentProxy::where('payable_id', $this->getPayableId())
            ->where('payable_type', $this->getPayableType())
            ->orderByDesc('id')
            ->take(1)
            ->first();
    }

    public function currentPayment(): BelongsTo
    {
        return $this->belongsTo(PaymentProxy::modelClass());
    }

    public function scopeWithCurrentPayment(Builder $query)
    {
        $query->addSelect(['current_payment_id' => PaymentProxy::select('id')
            ->whereColumn('payable_id', 'orders.id')
            ->where('payable_type', $this->getPayableType())
            ->orderByDesc('id')
            ->take(1)
        ])->with('currentPayment');
    }

    public function payments()
    {
        return $this->morphMany(PaymentProxy::modelClass(), 'payable');
    }

    public function shipments(): MorphToMany
    {
        return $this->morphToMany(ShipmentProxy::modelClass(), 'shippable');
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
