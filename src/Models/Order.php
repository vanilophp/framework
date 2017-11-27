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

    protected $fillable = ['status', 'user_id', 'billing_address_id', 'shipping_address_id', 'notes'];

    protected $enums = [
        'status' => 'OrderStatusProxy@enumClass'
    ];

    /**
     * @inheritdoc
     */
    public function getNumber()
    {
        return $this->number;
    }
}
