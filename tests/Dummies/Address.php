<?php
/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-26
 *
 */

namespace Vanilo\Order\Tests\Dummies;

use Konekt\Address\Models\Address as BaseAddress;
use Vanilo\Support\Traits\AddressModel;

class Address extends BaseAddress implements \Vanilo\Contracts\Address
{
    use AddressModel;

    protected $guarded = ['id'];
}
