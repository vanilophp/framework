<?php
/**
 * Contains the Product model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-27
 *
 */

namespace Vanilo\VaniloFramework\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class Product extends \Vanilo\Product\Models\Product
{
    use Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
