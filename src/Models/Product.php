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

use Vanilo\Contracts\Buyable;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;

class Product extends BaseProduct implements Buyable
{
    use BuyableModel;
}
