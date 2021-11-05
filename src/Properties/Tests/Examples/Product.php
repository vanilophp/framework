<?php

declare(strict_types=1);

/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Properties\Traits\HasPropertyValues;

class Product extends Model
{
    use HasPropertyValues;

    protected $guarded = ['id'];
}
