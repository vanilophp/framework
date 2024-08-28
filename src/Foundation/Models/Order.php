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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Konekt\Customer\Models\CustomerProxy;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Adjustments\Support\HasAdjustmentsViaRelation;
use Vanilo\Adjustments\Support\RecalculatesAdjustments;
use Vanilo\Channel\Contracts\Channel;
use Vanilo\Channel\Models\ChannelProxy;
use Vanilo\Contracts\Payable;
use Vanilo\Foundation\Traits\CanBeShipped;
use Vanilo\Order\Models\Order as BaseOrder;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Models\PaymentMethodProxy;
use Vanilo\Payment\Models\PaymentProxy;
use Vanilo\Shipment\Contracts\Shipment as ShipmentContract;
use Vanilo\Shipment\Contracts\ShippingMethod;
use Vanilo\Shipment\Models\ShippingMethodProxy;

/**
 * @property null|Payment $currentPayment is only available if order was fetched with withCurrentPayment() scope
 * @property null|int $channel_id
 * @property null|Channel $channel
 * @property null|int $shipping_method_id
 * @property null|ShippingMethod $shippingMethod
 * @property null|int $payment_method_id
 * @property null|PaymentMethod $paymentMethod
 * @property null|int $customer_id
 * @property null|string $payable_remote_id
 * @property-read float taxes_total
 * @property-read float shipping_total
 * @property-read float promotions_total
 * @property-read null|\Vanilo\Contracts\Customer $customer
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|ShipmentContract[] $shipments
 */
class Order extends BaseOrder implements Payable, Adjustable
{
    use CanBeShipped;
    use HasAdjustmentsViaRelation;
    use RecalculatesAdjustments;

    public static function findByPayableRemoteId(string $remoteId): ?Order
    {
        return static::where('payable_remote_id', $remoteId)->first();
    }

    public function getPayableId(): string
    {
        return (string) $this->id;
    }

    public function getPayableType(): string
    {
        return 'order';
    }

    /** Get the remote id used by the payment subsystem */
    public function getPayableRemoteId(): ?string
    {
        return $this->payable_remote_id;
    }

    /** Set the remote id used by the payment subsystem */
    public function setPayableRemoteId(string $remoteId): void
    {
        if ($this->exists) {
            self::update(['payable_remote_id' => $remoteId]);
        } else {
            $this->payable_remote_id = $remoteId;
        }
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
        return $this->currency ?? config('vanilo.foundation.currency.code');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethodProxy::modelClass());
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodProxy::modelClass());
    }

    public function getPaymentMethodId(): ?string
    {
        return $this->payment_method_id ? (string) $this->payment_method_id : null;
    }

    public function total(): float
    {
        return $this->preAdjustmentTotal() + $this->adjustments()->total();
    }

    /**
     * It equals to itemsTotal(), meaning that it contains the sum of the order items,
     * including the adjustments of the order items. Therefore, the adjustments of
     * the order are handled fully segregated from the order items' adjustments
     */
    public function preAdjustmentTotal(): float
    {
        return $this->itemsTotal();
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
            ->take(1),
        ])->with('currentPayment');
    }

    public function payments()
    {
        return $this->morphMany(PaymentProxy::modelClass(), 'payable');
    }

    protected function taxesTotal(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->adjustments()->byType(AdjustmentType::TAX())->total(true),
        );
    }

    protected function shippingTotal(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->adjustments()->byType(AdjustmentType::SHIPPING())->total(true),
        );
    }

    protected function promotionsTotal(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->adjustments()->byType(AdjustmentType::PROMOTION())->total(true),
        );
    }

    protected function getTotalAttribute(): float
    {
        return $this->total();
    }
}
