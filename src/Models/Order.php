<?php
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
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderStatus;

/**
 * @property int $id
 * @property string $number
 * @property string $notes
 * @property OrderStatus $status
 * @property null|int $billpayer_id
 * @property null|Billpayer $billpayer
 * @property null|int $user_id
 * @property null|User $user
 * @property null|Address $shippingAddress
 * @property null|int $shipping_address_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|Carbon $deleted_at
 * @property OrderItem[]|Collection $items
 * @method static Order create(array $attributes = [])
 * @method static Builder open()
 */
class Order extends Model implements OrderContract
{
    use CastsEnums;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'status' => 'OrderStatusProxy@enumClass'
    ];

    public function __construct(array $attributes = [])
    {
        // Set default status in case there was none given
        if (!isset($attributes['status'])) {
            $this->setDefaultOrderStatus();
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

    public function total()
    {
        return $this->items->sum('total');
    }

    public function scopeOpen(Builder $query)
    {
        return $query->whereIn('status', OrderStatusProxy::getOpenStatuses());
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
}
