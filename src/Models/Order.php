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
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Order\Contracts\Order as OrderContract;

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
            $this->setRawAttributes(
                array_merge(
                    $this->attributes, [
                        'status' => OrderStatusProxy::defaultValue()
                    ]
                ),
                true
            );

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

    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }
}
