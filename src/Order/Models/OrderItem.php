<?php

declare(strict_types=1);

/**
 * Contains the OrderItem model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */

namespace Vanilo\Order\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Vanilo\Order\Contracts\FulfillmentStatus;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderItem as OrderItemContract;
use Vanilo\Support\Traits\ConfigurableModel;
use Vanilo\Support\Traits\ConfigurationHasNoSchema;

/**
 * @property-read int $id
 * @property int $order_id
 * @property string $product_type
 * @property int $product_id
 * @property string $name
 * @property FulfillmentStatus $fulfillment_status
 * @property int $quantity
 * @property float $price
 * @property ?array $configuration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static OrderItem create(array $attributes = [])
 */
class OrderItem extends Model implements OrderItemContract
{
    use CastsEnums;
    use ConfigurableModel;
    use ConfigurationHasNoSchema;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $enums = [
        'fulfillment_status' => 'FulfillmentStatusProxy@enumClass',
    ];

    protected $casts = [
        'configuration' => 'json',
        'quantity' => 'int',
        'price' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    public function product()
    {
        return $this->morphTo();
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getProduct(): object
    {
        return $this->product;
    }

    public function getFulfillmentStatus(): FulfillmentStatus
    {
        return $this->fulfillment_status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return floatval($this->price);
    }

    public function total(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Property accessor alias to the total() method
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->total();
    }
}
