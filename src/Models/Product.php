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

namespace Konekt\Vanilo\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class Product extends \Konekt\Product\Models\Product
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
