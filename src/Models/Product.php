<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */


namespace Vanilo\Framework\Models;


use Vanilo\Cart\Contracts\Buyable;
use Vanilo\Cart\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;

class Product extends BaseProduct implements Buyable
{
    use BuyableModel;

}