<?php
/**
 * Contains the PaymentMethod class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-04-26
 *
 */

namespace Vanilo\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
