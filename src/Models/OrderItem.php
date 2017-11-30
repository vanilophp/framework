<?php
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


use Illuminate\Database\Eloquent\Model;
use Vanilo\Order\Contracts\OrderItem as OrderItemContract;

class OrderItem extends Model implements OrderItemContract
{
    protected $fillable = ['order_id', 'product_type', 'product_id', 'name', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    public function product()
    {
        return $this->morphTo();
    }

}
