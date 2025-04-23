<?php

declare(strict_types=1);

namespace Vanilo\Translation\Tests\Examples;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static Product create(array $attributes = [])
 */
class Product extends Model
{

    protected $table = 'products_translatable_test';

    protected $guarded = ['id'];
}
