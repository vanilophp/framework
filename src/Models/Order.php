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


use Illuminate\Database\Eloquent\Model;
use Konekt\Address\Models\AddressProxy;
use Konekt\Enum\Eloquent\CastsEnums;
use Traversable;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderStatus;

class Order extends Model implements OrderContract
{
    use CastsEnums;

    protected $fillable = ['number', 'status', 'user_id', 'billing_address_id', 'shipping_address_id', 'notes'];

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

    /**
     * @inheritdoc
     */
    public function getNumber()
    {
        return $this->number;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @inheritdoc
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function getItems(): Traversable
    {
        return $this->items;
    }

    public function billingAddress()
    {
        return $this->belongsTo(AddressProxy::modelClass());
    }

    public function shippingAddress()
    {
        return $this->belongsTo(AddressProxy::modelClass());
    }


    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }

    /**
     * Sets the default order status in raw attributes
     *
     */
    protected function setDefaultOrderStatus()
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes, [
                    'status' => OrderStatusProxy::defaultValue()
                ]
            ),
            true
        );
    }
}
