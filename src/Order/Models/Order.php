<?php

declare(strict_types=1);

/**
 * Contains the Order model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */

namespace Vanilo\Order\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Konekt\Address\Models\AddressProxy;
use Konekt\Enum\Eloquent\CastsEnums;
use Konekt\User\Contracts\User;
use Konekt\User\Models\UserProxy;
use Traversable;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Order\Contracts\FulfillmentStatus;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderStatus;

/**
 * @property int $id
 * @property string $number
 * @property string $notes
 * @property OrderStatus $status
 * @property FulfillmentStatus $fulfillment_status
 * @property null|string $language The two-letter ISO 639-1 code
 * @property null|string $currency The 3 letter currency code
 * @property null|int $billpayer_id
 * @property null|Billpayer $billpayer
 * @property null|int $user_id
 * @property null|User $user
 * @property null|Address $shippingAddress
 * @property null|int $shipping_address_id
 * @property null|Carbon $ordered_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Carbon $deleted_at
 * @property OrderItem[]|Collection $items
 * @method static static create(array $attributes = [])
 * @method static Builder open()
 * @method static Builder ofUser(User|int|string $user)
 */
class Order extends Model implements OrderContract
{
    use CastsEnums;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'status' => 'OrderStatusProxy@enumClass',
        'fulfillment_status' => 'FulfillmentStatusProxy@enumClass',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        // Set default status in case there was none given
        if (!isset($attributes['status'])) {
            $this->setDefaultOrderStatus();
        }
        if (!isset($attributes['fulfillment_status'])) {
            $this->setDefaultFulfillmentStatus();
        }

        parent::__construct($attributes);
    }

    public static function findByNumber(string $orderNumber): ?OrderContract
    {
        return static::where('number', $orderNumber)->first();
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    public function getBillpayer(): ?BillPayer
    {
        return $this->billpayer;
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    /** The two-letter ISO 639-1 code */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getItems(): Traversable
    {
        return $this->items;
    }

    public function billpayer()
    {
        return $this->belongsTo(BillpayerProxy::modelClass());
    }

    public function shippingAddress()
    {
        return $this->belongsTo(AddressProxy::modelClass());
    }

    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }

    public function total(): float
    {
        return $this->items->sum('total');
    }

    public function itemsTotal(): float
    {
        // Total and items total are the same on this level, but they can differ as soon as the
        // Order gains the optional adjustable behavior which can add non-item related costs
        // or discounts eg. shipping fee, promotion, coupon, etc. that modify order total
        return $this->items->sum('total');
    }

    public function getFulfillmentStatus(): FulfillmentStatus
    {
        return $this->fulfillment_status;
    }

    public function scopeOpen(Builder $query)
    {
        return $query->whereIn('status', OrderStatusProxy::getOpenStatuses());
    }

    public function scopeOfUser($query, $user)
    {
        return $query->where('user_id', is_object($user) ? $user->id : $user);
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if (null === $order->ordered_at) {
                $order->ordered_at = Carbon::now();
            }
        });
    }

    protected function setDefaultOrderStatus()
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'status' => OrderStatusProxy::defaultValue()
                ]
            ),
            true
        );
    }

    protected function setDefaultFulfillmentStatus()
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'fulfillment_status' => FulfillmentStatusProxy::defaultValue()
                ]
            ),
            true
        );
    }
}
