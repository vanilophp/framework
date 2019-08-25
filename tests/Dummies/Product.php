<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */

namespace Vanilo\Cart\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Buyable;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Support\Traits\BuyableNoImage;

class Product extends Model implements Buyable
{
    use BuyableModel, BuyableNoImage;

    protected $guarded = ['id'];
}
