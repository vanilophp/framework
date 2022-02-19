<?php

declare(strict_types=1);

/**
 * Contains the TestLinkableProduct class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-18
 *
 */

namespace Vanilo\Links\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Links\Traits\Linkable;

/**
 * @method static TestLinkableProduct create(array $attributes)
 */
class TestLinkableProduct extends Model
{
    use Linkable;

    protected $guarded = ['id'];

    protected $table = 'test_products';
}
