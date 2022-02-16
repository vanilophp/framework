<?php

declare(strict_types=1);

/**
 * Contains the TestProduct class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Tests\Dummies;

use Illuminate\Database\Eloquent\Model;

class TestProduct extends Model
{
    protected $guarded = ['id'];
}
