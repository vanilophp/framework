<?php

declare(strict_types=1);

/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-04
 *
 */

namespace Vanilo\Category\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Category\Traits\HasTaxons;

class Product extends Model
{
    use HasTaxons;

    protected $guarded = ['id'];
}
