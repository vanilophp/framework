<?php
/**
 * Contains the SizedProduct Dummy class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-15
 *
 */

namespace Vanilo\Cart\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Buyable;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Support\Traits\BuyableNoImage;

class SizedProduct extends Model implements Buyable
{
    use BuyableModel, BuyableNoImage;

    protected $guarded = ['id'];
}
